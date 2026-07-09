"use strict";(globalThis.webpackChunkwp_event_solution=globalThis.webpackChunkwp_event_solution||[]).push([[4218],{38296(e,t,o){o.d(t,{A:()=>c});var n=o(51609),r=o(56427),i=o(92911),l=o(18062),a=o(27154);function c(e){const{title:t}=e;return(0,n.createElement)(r.Fill,{name:a.PQ},(0,n.createElement)(i.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,n.createElement)(l.A,{title:t})))}},86952(e,t,o){o.d(t,["b",0,{prefix:"eve",theme:{primaryColor:"#6b2ee5",secondaryColor:"oklch(0.97 0 0)",successColor:"#10b981",warningColor:"#f59e0b",errorColor:"#ef4444"},helpDocUrl:"https://themewinter.com/docs/plugins/plugin-docs/email-settings/automation/",helpVideoUrl:"https://www.youtube.com/watch?v=9gV6MZeT164",translationDomain:"eventin"}])},94218(e,t,o){o.r(t);var n=o(51609),r=o(29491),i=o(47143),l=o(27723),a=o(98731),c=o(47767),s=o(75093),d=o(38296),u=o(86952),p=o(89279);const f=(0,i.withSelect)(e=>{const t=e("eventin/global");return{settings:t.getSettings(),isLoading:t.isResolving("getSettings")}}),b=(0,r.compose)(f)(function(e){(0,a.fB)(u.b);const{settings:t,isLoading:o}=e||{},r=(0,c.Zp)();return t&&"on"!==t?.modules?.automation?(0,n.createElement)(c.C5,{to:"/dashboard",replace:!0}):(0,n.createElement)(a.gI,null,(0,n.createElement)(d.A,{title:(0,l.__)("Automation","eventin")}),(0,n.createElement)(p.D,null,(0,n.createElement)(a.eb,{onEdit:e=>r(`/automation/${e}/edit`)})),(0,n.createElement)(s._W,null))});o.d(t,["default",0,b])},89279(e,t,o){const n=o(69815).A.div`
	/* background-color: #f4f6fa; */
	padding: 12px 32px;

	.automation-list__header .ant-btn-primary {
		height: 40px;
	}
	.automation-list .bulk-actions-bar .bulk-delete-btn {
		margin-right: 16px;
		background-color: #fff;
		border: 1px solid #ff4d4f;
		color: #ff4d4f;
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
		border: 1px solid #d9d9d9 !important;
	}

	select.notif-flow-select.notif-flow-select--middle.notif-flow-select {
		border: 1px solid #d9d9d9 !important;
	}
	.notif-flow-switch-checked .notif-flow-switch-track {
		background-color: #6b2ee5;
	}
	@media ( prefers-color-scheme: dark ) {
		.notif-flow-switch-track {
			background-color: #c3c4c7;
		}

		.notif-flow-table input[type='checkbox'],
		.notif-flow-table input[type='radio'] {
			accent-color: #6b2ee5;
			background-color: transparent;
			border: 1px solid #d9d9d9 !important;
		}
		input[type='checkbox']:checked::before {
			border-radius: 3px;
			background-color: #6b2ee5;
		}
	}
`;o.d(t,["D",0,n])}}]);