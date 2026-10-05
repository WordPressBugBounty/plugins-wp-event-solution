"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[4218],{38296(e,t,r){r.d(t,{A:()=>c});var o=r(51609),a=r(56427),n=r(92911),i=r(18062),l=r(27154);function c(e){const{title:t}=e;return(0,o.createElement)(a.Fill,{name:l.PQ},(0,o.createElement)(n.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,o.createElement)(i.A,{title:t})))}},86952(e,t,r){r.d(t,["T",0,(e="light")=>({prefix:"eve",theme:{mode:e,primaryColor:"#6b2ee5",secondaryColor:"oklch(0.97 0 0)",successColor:"#10b981",warningColor:"#f59e0b",errorColor:"#ef4444",colors:{bgPrimary:"var(--etn-surface, #ffffff)",bgSecondary:"var(--etn-bg-subtle, #f9fafb)",bgTertiary:"var(--etn-bg-muted, #f3f4f6)",bgCanvas:"var(--etn-bg-page, #f8fcfe)",textPrimary:"var(--etn-text, #1f2937)",textSecondary:"var(--etn-text-muted, #6b7280)",textTertiary:"var(--etn-text-disabled, #9ca3af)",borderPrimary:"var(--etn-border, #e5e7eb)",borderSecondary:"var(--etn-border, #d1d5db)",borderLight:"var(--etn-border-subtle, #f0f0f0)",hoverBg:"var(--etn-bg-muted, #f3f4f6)",activeBg:"var(--etn-fill, #e5e7eb)",disabledBg:"var(--etn-bg-subtle, #f9fafb)",disabledText:"var(--etn-text-disabled, #9ca3af)"}},helpDocUrl:"https://themewinter.com/docs/plugins/plugin-docs/email-settings/automation/",helpVideoUrl:"https://www.youtube.com/watch?v=9gV6MZeT164",translationDomain:"eventin"})])},94218(e,t,r){r.r(t);var o=r(51609),a=r(29491),n=r(47143),i=r(27723),l=r(98731),c=r(47767),d=r(75093),s=r(38296),f=r(86952),b=r(45265),u=r(89279);const p=(0,n.withSelect)(e=>{const t=e("eventin/global");return{settings:t.getSettings(),isLoading:t.isResolving("getSettings")}}),g=(0,a.compose)(p)(function(e){const{mode:t}=(0,b.G6)();(0,l.fB)((0,f.T)(t));const{settings:r,isLoading:a}=e||{},n=(0,c.Zp)();return r&&"on"!==r?.modules?.automation?(0,o.createElement)(c.C5,{to:"/dashboard",replace:!0}):(0,o.createElement)(l.gI,null,(0,o.createElement)(s.A,{title:(0,i.__)("Automation","eventin")}),(0,o.createElement)(u.D,null,(0,o.createElement)(l.eb,{onEdit:e=>n(`/automation/${e}/edit`)})),(0,o.createElement)(d._W,null))});r.d(t,["default",0,g])},89279(e,t,r){const o=r(69815).A.div`
	/* background-color: #f4f6fa; */
	padding: 12px 32px;

	.automation-list__header .ant-btn-primary {
		height: 40px;
	}
	.automation-list .bulk-actions-bar .bulk-delete-btn {
		margin-right: 16px;
		background-color: var(--etn-surface, #fff);
		border: 1px solid #ff4d4f;
		color: var(--etn-danger, #ff4d4f);
	}

	input.notif-flow-input,
	input.automation-list__header
		.filter-search-group
		.automation-search
		.ant-input-outlined,
	input.automation-list__header
		.filter-search-group
		.status-filter-select
		.ant-select-selector {
		border: 1px solid var(--etn-border, #d9d9d9) !important;
	}

	select.notif-flow-select.notif-flow-select--middle.notif-flow-select {
		border: 1px solid var(--etn-border, #d9d9d9) !important;
	}
	.notif-flow-switch-checked .notif-flow-switch-track {
		background-color: #6b2ee5;
	}
	@media ( prefers-color-scheme: dark ) {
		.notif-flow-switch-track {
			background-color: var(--etn-fill, #c3c4c7);
		}

		.notif-flow-table input[type='checkbox'],
		.notif-flow-table input[type='radio'] {
			accent-color: #6b2ee5;
			background-color: transparent;
			border: 1px solid var(--etn-border, #d9d9d9) !important;
		}
		input[type='checkbox']:checked::before {
			border-radius: 3px;
			background-color: #6b2ee5;
		}
	}
`;r.d(t,["D",0,o])}}]);