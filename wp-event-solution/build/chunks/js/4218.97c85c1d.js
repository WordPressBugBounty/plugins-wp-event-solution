"use strict";(Array.isArray(globalThis.webpackChunkwp_event_solution)?globalThis.webpackChunkwp_event_solution:globalThis.webpackChunkwp_event_solution=[]).push([[4218],{38296(e,t,o){o.d(t,{A:()=>s});var a=o(51609),r=o(56427),n=o(92911),l=o(18062),i=o(27154);function s(e){const{title:t}=e;return(0,a.createElement)(r.Fill,{name:i.PQ},(0,a.createElement)(n.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,a.createElement)(l.A,{title:t})))}},86952(e,t,o){var a=o(13179);o.d(t,["T",0,(e="light")=>({prefix:"eve",theme:{mode:e,primaryColor:"#6b2ee5",secondaryColor:"oklch(0.97 0 0)",successColor:"#10b981",warningColor:"#f59e0b",errorColor:"#ef4444",colors:{bgPrimary:"var(--etn-surface, #ffffff)",bgSecondary:"var(--etn-bg-subtle, #f9fafb)",bgTertiary:"var(--etn-bg-muted, #f3f4f6)",bgCanvas:"var(--etn-bg-page, #f8fcfe)",textPrimary:"var(--etn-text, #1f2937)",textSecondary:"var(--etn-text-muted, #6b7280)",textTertiary:"var(--etn-text-disabled, #9ca3af)",borderPrimary:"var(--etn-border, #e5e7eb)",borderSecondary:"var(--etn-border, #d1d5db)",borderLight:"var(--etn-border-subtle, #f0f0f0)",hoverBg:"var(--etn-bg-muted, #f3f4f6)",activeBg:"var(--etn-fill, #e5e7eb)",disabledBg:"var(--etn-bg-subtle, #f9fafb)",disabledText:"var(--etn-text-disabled, #9ca3af)"}},helpDocUrl:"https://themewinter.com/docs/plugins/plugin-docs/email-settings/automation/",helpVideoUrl:"https://www.youtube.com/watch?v=9gV6MZeT164",translationDomain:"eventin",emailExtension:a.A})])},13179(e,t,o){o.d(t,{A:()=>d});var a=o(51609),r=o(86087),n=o(27723),l=o(50400),i=o(51643),s=o(64282);const c="emailTemplateMode";function d({triggerType:e,trigger:t,values:o,setFieldValue:d}){const u=!!t?.email_design,m=t?.email_formats||{},f=o?.receiverType||"",b=o?.[c]||"",p=u?b||"default":"custom"===b?"custom":"plain",[g,v]=(0,r.useState)({html:"",error:!1,loading:!1}),[h,w]=(0,r.useState)(!1),_=(0,r.useRef)();(0,r.useEffect)(()=>{if(u&&"default"===p&&f)return clearTimeout(_.current),_.current=setTimeout(async()=>{v(e=>({...e,loading:!0}));try{const t=await s.A.automationEmail.preview({trigger:e,receiver:f,subject:o?.subject||"",body:o?.body||"",mode:b});v({html:t?.html||"",error:!1,loading:!1})}catch(e){v(e=>({...e,error:!0,loading:!1}))}},600),()=>clearTimeout(_.current)},[u,p,f,e,o?.subject,o?.body,b]);const y=()=>{const e=m[f];w(!1),e&&(d("body",e.body),o?.subject||d("subject",e.subject))},E=u?[{value:"default",label:(0,n.__)("Default design","wp-event-solution")},{value:"custom",label:(0,n.__)("Custom","wp-event-solution")}]:[{value:"plain",label:(0,n.__)("Plain text (as today)","wp-event-solution")},{value:"custom",label:(0,n.__)("Custom (with header and footer)","wp-event-solution")}];return(0,a.createElement)("div",{className:"etn-email-design",style:{marginBottom:16}},(0,a.createElement)("div",{className:"notif-flow-form-item-label",style:{fontWeight:600,marginBottom:6}},(0,n.__)("Email design","wp-event-solution")),(0,a.createElement)(i.Ay.Group,{className:"etn-email-design-mode",value:p,options:E,onChange:e=>d(c,"plain"===e.target.value?"":e.target.value)}),("custom"===p||!u)&&(0,a.createElement)("div",{style:{marginTop:8}},(0,a.createElement)(l.A,{className:"etn-email-use-suggestion",size:"small",disabled:!f||!m[f],onClick:()=>{var e;e=o?.body,e&&String(e).replace(/<[^>]*>/g,"").trim()?w(!0):y()}},(0,n.__)("Use suggested format","wp-event-solution")),!f&&(0,a.createElement)("span",{style:{marginLeft:8,color:"#6b7280",fontSize:12}},(0,n.__)("Choose a receiver first.","wp-event-solution")),h&&(0,a.createElement)("div",{className:"etn-email-suggestion-confirm",style:{marginTop:8,fontSize:12}},(0,n.__)("Replace the current text with the suggested format?","wp-event-solution")," ",(0,a.createElement)(l.A,{size:"small",type:"primary",onClick:y},(0,n.__)("Replace","wp-event-solution"))," ",(0,a.createElement)(l.A,{size:"small",onClick:()=>w(!1)},(0,n.__)("Cancel","wp-event-solution")))),"default"===p&&u&&(0,a.createElement)("div",{className:"etn-email-preview",style:{marginTop:10}},(0,a.createElement)("div",{style:{color:"#6b7280",fontSize:12,marginBottom:4}},(0,n.__)("Preview with sample data. Your text appears above the design.","wp-event-solution")),!f&&(0,a.createElement)("div",{style:{color:"#6b7280",fontSize:12}},(0,n.__)("Choose a receiver to see the preview.","wp-event-solution")),g.error&&(0,a.createElement)("div",{className:"etn-email-preview-error",style:{color:"#b42318",fontSize:12}},(0,n.__)("Preview not available right now.","wp-event-solution")),f&&g.html&&(0,a.createElement)("iframe",{className:"etn-email-preview-frame",title:(0,n.__)("Email preview","wp-event-solution"),srcDoc:g.html,sandbox:"",style:{width:"100%",height:420,border:"1px solid #e5e7eb",borderRadius:6,background:"#fff",opacity:g.loading?.6:1}})))}},94218(e,t,o){o.r(t);var a=o(51609),r=o(29491),n=o(47143),l=o(27723),i=o(98731),s=o(47767),c=o(75093),d=o(38296),u=o(86952),m=o(45265),f=o(89279);const b=(0,n.withSelect)(e=>{const t=e("eventin/global");return{settings:t.getSettings(),isLoading:t.isResolving("getSettings")}}),p=(0,r.compose)(b)(function(e){const{mode:t}=(0,m.G6)();(0,i.fB)((0,u.T)(t));const{settings:o,isLoading:r}=e||{},n=(0,s.Zp)();return o&&"on"!==o?.modules?.automation?(0,a.createElement)(s.C5,{to:"/dashboard",replace:!0}):(0,a.createElement)(i.gI,null,(0,a.createElement)(d.A,{title:(0,l.__)("Automation","wp-event-solution")}),(0,a.createElement)(f.D,null,(0,a.createElement)(i.eb,{onEdit:e=>n(`/automation/${e}/edit`)})),(0,a.createElement)(c._W,null))});o.d(t,["default",0,p])},89279(e,t,o){const a=o(69815).A.div`
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
`;o.d(t,["D",0,a])}}]);