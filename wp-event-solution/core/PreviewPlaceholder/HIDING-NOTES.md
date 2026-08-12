# How the preview-placeholder event is kept out of sight

The plugin ships a hidden demo event — *"Applied AI & Machine Learning Summit 2026"* —
so the Template Builder always has something to preview when a template has no event
selected. That event, plus its speakers, organizers, and schedules, must **never**
appear anywhere a real visitor or admin browses. This note records where each
listing surface is filtered so the placeholder stays invisible.

## What gets created

`PreviewPlaceholderEventSeeder` (run once via the 4.1.16 upgrader) creates:

- 1 `etn` event  → ID stored in option `etn_preview_placeholder_event_id`
- N `etn-schedule` posts → IDs in `etn_preview_placeholder_schedule_ids`
- N speaker/organizer **users** → IDs in `etn_preview_placeholder_user_ids`

Everything is tagged with meta `_etn_preview_placeholder = 1`, and the users also get
`hide_user = 1`.

## Where each surface is filtered

| Surface | Mechanism | Where |
|---|---|---|
| Front-end event/schedule archives, search, feeds, taxonomy | `pre_get_posts` adds `post__not_in` | `PreviewPlaceholderVisibility::hide_from_public_queries` |
| Event/schedule **shortcodes, Elementor widgets, blocks** | `pre_get_posts` adds `post__not_in` | `PreviewPlaceholderVisibility::hide_from_secondary_queries` |
| Front-end **AJAX** listers (Pro Event Locations map, BuddyBoss event list) | same `pre_get_posts` handler — reached because `is_admin_context()` treats AJAX as front-end | `PreviewPlaceholderVisibility::is_admin_context` |
| Speaker/organizer **shortcodes, Elementor widgets, blocks** | `pre_get_users` adds `exclude` | `PreviewPlaceholderVisibility::hide_from_user_queries` |
| Direct URL to the event / schedule / speaker page | `template_redirect` → 404 | `PreviewPlaceholderVisibility::block_direct_access` |
| Admin WP Users list table | `users_list_table_query_args` (existing `hide_user` filter) | `core/speaker/hooks.php` |
| REST event list | `post__not_in` in the query args | `EventController` (~line 498) |
| REST schedule list | `post__not_in` in the query args | `ScheduleController` (~line 197) |
| Admin Speaker/Organizer REST list | `exclude` **and** removal from `include` | `SpeakerController` (~line 267) |
| Event / schedule / speaker / organizer **Export** | `PreviewPlaceholder::strip_post_ids()` / `strip_user_ids()` on the final ID list | `EventController::export_items`, `ScheduleController::export_items`, `SpeakerController::export_items` + `export_organizers` |

## The subtle one: the admin Speakers / Organizers tabs

`WP_User_Query` applies `include` **OR** `exclude`, never both — when `include` is set,
`exclude` is silently ignored (`if include … elseif exclude`).

- The **unfiltered** speaker list (`/eventin/v2/speakers` with no category) never sets
  `include`, so adding placeholders to `exclude` is enough.
- The **Speakers / Organizers tabs** call `/eventin/v2/speakers?category=speaker`
  (or `organizer`). That category param makes `get_items()` populate `include` with
  every user of that role — placeholders included — so `exclude` alone does nothing and
  the demo people reappear on those tabs.

The fix in `SpeakerController::get_items()` therefore **also removes the placeholder IDs
from `include`** (and forces zero results with `include => [-1]` when they were the only
members). This is the fix for "demo speaker/organizer still showing in the admin list".

## Why the preview still works

Events are rendered in a preview by ID (`new Event_Model( $id )`), and their
speakers/organizers by an explicit `include`. Since `WP_User_Query` lets `include` win
over `exclude`, and the post filters skip queries that pin a placeholder ID, the
placeholder's own records still render inside its template preview even though every
*listing* hides them.

## A gotcha when testing after a build swap

The admin SPA holds fetched lists in memory. After replacing the plugin build on a
running site, **hard-refresh** the admin page before trusting what the list shows — a
stale tab can still display the pre-update response. Verify against a fresh request
(`curl`/incognito) when in doubt.

## The other subtle one: `is_admin()` is true during AJAX

`wp-admin/admin-ajax.php` defines `WP_ADMIN`, so **`is_admin()` returns true for every
AJAX request** — including the `wp_ajax_nopriv_*` handlers that render front-end markup
for logged-out visitors. `is_admin_context()` originally returned `is_admin()` with only
an Elementor carve-out, so both `pre_get_posts` handlers bailed out and any front-end
AJAX lister queried unfiltered. That is how the demo event reached the "Nearby Events"
list of Pro's Event Locations map widget
(`eventin-pro/widgets/event-locations/actions/ajax-action.php` →
`query_all_locations()` runs its own `WP_Query( [ 'post_type' => 'etn' ] )`).
Pro's BuddyBoss `etn_bp_event_list` handler has the same shape.

`is_admin_context()` therefore treats **every** AJAX request as front-end. Nothing in
wp-admin depends on the placeholder surviving an admin-ajax query — the dashboard reads
its lists over the `eventin/v2` REST API, which both handlers skip separately. This also
subsumes the old Elementor editor-AJAX carve-out.

Regression cover: `tests/phpunit/tests/ReleasePreviewPlaceholderAjaxTest.php`.

**When adding any new front-end AJAX handler that lists events, you get this exclusion
for free** — it runs through `pre_get_posts`. Query paths that bypass `WP_Query`
(raw `$wpdb`, or REST, which is deliberately skipped) still need the exclusion applied
at the query source, the way `EventController`, `ScheduleController`,
`core/event/api.php` and `Helper::get_events_by_date()` each do.

## The third subtle one: Export builds its own ID list

The Export routes never run a listing query — the no-ids branch calls
`Post_Model::get_ids()` / `User_Model::get_ids()` and hands the raw ID list straight to
an exporter. Those models apply no exclusion, and because export runs over
`eventin/v2`, the `pre_get_posts` / `pre_get_users` net bails out. The demo event, its
schedules and its speakers/organizers therefore landed in the downloaded CSV/JSON —
which users re-import, spreading the demo data to real sites.

Fixed by `PreviewPlaceholder::strip_post_ids()` / `strip_user_ids()` applied to the
final `$ids` in all four export methods (so it covers the explicit-`ids` branch too — a
crafted request can name the placeholder even though the admin list never offers it).

Regression cover: `tests/phpunit/tests/ReleasePreviewPlaceholderExportTest.php`. Those
tests detach the two `pre_get_*` handlers to emulate the REST bail; without that the
net hides the placeholder and the test passes against unfixed code.

## Not covered: orphaned or duplicate copies

Everything above keys off the IDs in `etn_preview_placeholder_event_id` /
`_schedule_ids`. A *second*, unregistered copy of the demo event — one that carries no
`_etn_preview_placeholder` marker and isn't named by the option — is indistinguishable
from a real event and will show everywhere. That is a separate defect with its own fix
on `develop` (`31486fe11` DbLock mutex, `203d78224` duplicate cleanup, `377b73502`
orphaned records); **those commits are not in `release/4.1.19`.** If a demo event still
appears after this fix, check whether it is the registered ID before reopening this one.
