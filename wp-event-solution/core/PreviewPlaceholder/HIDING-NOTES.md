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
| Speaker/organizer **shortcodes, Elementor widgets, blocks** | `pre_get_users` adds `exclude` | `PreviewPlaceholderVisibility::hide_from_user_queries` |
| Direct URL to the event / schedule / speaker page | `template_redirect` → 404 | `PreviewPlaceholderVisibility::block_direct_access` |
| Admin WP Users list table | `users_list_table_query_args` (existing `hide_user` filter) | `core/speaker/hooks.php` |
| REST event list | `post__not_in` in the query args | `EventController` (~line 498) |
| REST schedule list | `post__not_in` in the query args | `ScheduleController` (~line 197) |
| Admin Speaker/Organizer REST list | `exclude` **and** removal from `include` | `SpeakerController` (~line 267) |

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

## The one path still not filtered

The **legacy v1 REST event collection** (`core/event/api.php`) is not wired to the
placeholder exclusion. All of the plugin's own front-end widgets/blocks are
server-rendered PHP (covered above), so this only matters for a headless/JS consumer
reading that endpoint directly. Patch it the same way (`post__not_in`) if that ever
becomes a concern.
