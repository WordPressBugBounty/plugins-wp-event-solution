"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[828],{70940(e,t,n){var a=n(51609),l=n(86087);n.d(t,["A",0,({hasMore:e,onLoadMore:t,scrollContainerSelector:n=null})=>{const i=(0,l.useRef)(null);return(0,l.useEffect)(()=>{if(!i.current||!t)return;const a=n?document.querySelector(n):null,l=new IntersectionObserver(n=>{n[0].isIntersecting&&e&&t()},{root:a,threshold:.1});return l.observe(i.current),()=>l.disconnect()},[e,t,n]),e?(0,a.createElement)("div",{ref:i,style:{height:1}}):null}])},49636(e,t,n){var a=n(51609),l=n(27723),i=n(44100);const r=(0,n(6836).QF)("/images/events/ticket-image.webp"),o=[(0,l.__)("Select a ticket template that fits your event","eventin"),(0,l.__)("Customize the layout, colors, and ticket details","eventin"),(0,l.__)("Save & assign the ticket template to your event","eventin")];n.d(t,["A",0,()=>(0,a.createElement)(i.A,{image:r,title:(0,l.__)("Create your event tickets","eventin"),steps:o,docLink:"https://themewinter.com/docs/plugins/plugin-docs/template-builder/ticket-template/",docLinkText:(0,l.__)("Learn more","eventin")})])},55392(e,t,n){var a=n(51609),l=n(27723),i=n(11721),r=n(428),o=n(54725),s=n(7638),d=n(50892);n.d(t,["A",0,({name:e,selected:t,isStatic:n,onTemplateSelect:c,menuItems:p,actionLabel:m=(0,l.__)("Select","eventin"),loading:u=!1})=>(0,a.createElement)(d.JY,null,(0,a.createElement)("p",{className:"title"},e),t?(0,a.createElement)(d.Ql,null,(0,a.createElement)(o.H46,{width:16,height:16}),(0,l.__)("Selected","eventin")):(0,a.createElement)(d.Po,{className:"eve-select-btn",onClick:u?void 0:c,disabled:u,style:u?{gap:"6px",opacity:.7,cursor:"default"}:void 0},u&&(0,a.createElement)(r.A,{size:"small"}),m),!n&&p.length>0&&(0,a.createElement)(i.A,{popupRender:()=>(0,a.createElement)(d.cb,null,p.map(e=>(0,a.createElement)(d.eV,{key:e.key,danger:e.danger,onClick:e.onClick},e.icon,e.label))),trigger:["click"],placement:"bottomRight"},(0,a.createElement)(s.Ay,{sx:{display:"flex",alignItems:"center",gap:"6px",height:"34px",padding:"0 10px",borderRadius:"6px",fontSize:"14px",fontWeight:500,color:"var(--etn-text, #170A32)",background:"var(--etn-bg-subtle, #F1F0F5)",border:"1px solid var(--etn-border-subtle, #E5EAF2)"},variant:s.Vt,className:"etn-template-action-btn"},(0,a.createElement)(o.RtS,null))))])},22693(e,t,n){var a=n(51609),l=n(27723),i=n(92911),r=n(54725),o=n(27154),s=n(7638);const d={display:"flex",alignItems:"center",justifyContent:"center",width:"48px",height:"48px",minWidth:"48px",padding:0,borderRadius:"50%",border:"none",background:"var(--etn-surface, rgba( 255, 255, 255, 0.92 ))",color:"var(--etn-text, #170A32)",boxShadow:"0 8px 20px rgba( 23, 10, 50, 0.25 )",cursor:"pointer",transition:"transform 0.22s ease, background 0.22s ease, color 0.22s ease, box-shadow 0.22s ease","&:hover":{background:`${o.VG} !important`,color:"#ffffff !important",transform:"translateY( -3px ) scale( 1.06 )",boxShadow:"0 12px 26px rgba( 107, 46, 229, 0.42 )"}};n.d(t,["A",0,({previewLink:e,editLink:t,previewLabel:n=(0,l.__)("Preview","eventin")})=>(0,a.createElement)(i.A,{className:"template-name-overlay",align:"center",justify:"center",gap:14},(0,a.createElement)(s.Ay,{sx:d,title:n,"aria-label":n,onClick:t=>{t.stopPropagation(),window.open(e,"_blank")}},(0,a.createElement)(r.XBO,{width:18,height:18})),Boolean(t)&&(0,a.createElement)(s.Ay,{sx:d,title:(0,l.__)("Edit","eventin"),"aria-label":(0,l.__)("Edit","eventin"),onClick:e=>{e.stopPropagation(),window.open(t,"_blank")}},(0,a.createElement)(r.xjh,{width:18,height:18})))])},10076(e,t,n){var a=n(51609),l=n(27723),i=n(54725),r=n(6836),o=n(55392),s=n(22693),d=n(50892);n.d(t,["A",0,({template:e,selectedTemplateId:t,onTemplateSelect:n,handleDeleteConfirm:c,onDuplicate:p,templateType:m="event"})=>{const{thumbnail:u,isStatic:g,id:v,name:b,edit_link:f,preview_link:h,remote_template_id:y}=e,x=t==v,E=u||(0,r.QF)("/images/landing_placeholder.webp"),_=!y,w=[...p?[{label:(0,l.__)("Duplicate","eventin"),key:"1",icon:(0,a.createElement)(i.jaP,{width:"16",height:"16"}),onClick:()=>p(e)}]:[],..._?[{label:(0,l.__)("Delete","eventin"),key:"2",danger:!0,icon:(0,a.createElement)(i.SUY,{width:"16",height:"16"}),onClick:()=>c(v,g)}]:[]];return(0,a.createElement)(d.c7,{isSelected:x},(0,a.createElement)(d.YM,{isCustom:e?.is_custom,isEventType:"event"===m,isCertificateType:"certificate"===m},(0,a.createElement)("img",{src:E,alt:b,className:"eve-template-img"}),(0,a.createElement)(s.A,{previewLink:h,editLink:f})),(0,a.createElement)(o.A,{name:b,selected:x,isStatic:g,onTemplateSelect:n,menuItems:w}))}])},50892(e,t,n){var a=n(27154),l=n(69815);const i=l.A.div`
	position: relative;
	display: block;
	border-radius: 12px;
	border: ${({isSelected:e})=>e?`2px solid ${a.VG}`:"2px solid var(--etn-border-subtle, #E5EAF2)"};
	overflow: hidden;
	padding:4px;
	box-shadow: 0 10px 28px rgba( 23, 10, 50, 0.10 );
	transition:
		transform 0.28s ease,
		box-shadow 0.28s ease,
		border-color 0.28s ease;

	&:hover {
		transform: translateY( -6px );
		box-shadow: 0 18px 40px rgba( 23, 10, 50, 0.16 );
		border-color: ${({isSelected:e})=>e?a.VG:"var(--etn-primary-border, #C9B6F5)"};
	}

	&:hover .eve-select-btn {
		display: flex;
	}
`,r=l.A.div`
	position: relative;
	height: ${({isEventType:e})=>!1===e?"280px":"400px"};
	width: 100%;
	background: var(--etn-bg-subtle, #f5f5f5) center/cover no-repeat;
	overflow: hidden;
	display: flex;
	align-items: center;
	justify-content: center;

	&:hover {
		.template-name-overlay {
			opacity: 1;
			visibility: visible;
		}
	}

	.eve-template-img {
		width: 100%;
		height: 100%;
		  object-fit: ${({isEventType:e,isCertificateType:t})=>e||t?"cover":"contain"};
		object-position: top center;
		border-radius: 8px;
	}

	.template-name-overlay {
		position: absolute;
		inset: 0;
		display: flex;
		align-items: center;
		justify-content: center;
		padding-bottom: 56px;
		border-radius: 8px;
		background: linear-gradient(
			180deg,
			rgba( 23, 10, 50, 0.15 ) 0%,
			rgba( 23, 10, 50, 0.55 ) 100%
		);
		opacity: 0;
		transition:
			opacity 0.28s ease,
			visibility 0.28s ease;
		visibility: hidden;
	}
`,o=l.A.div`
	position: absolute;
	left: 0;
	right: 0;
	bottom: 0;
	z-index: 4;
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 14px;
	gap: 8px;
	border-bottom-left-radius: 10px;
	border-bottom-right-radius: 10px;
	background: var(--etn-surface, #ffffffdb);
	pointer-events: none;

	& > * {
		pointer-events: auto;
	}

	.title {
		margin: 0;
		font-size: 15px;
		font-weight: 600;
		color: var(--etn-text, #170a32);
		flex: 1;
		min-width: 0;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}
`,s=l.A.button`
	flex-shrink: 0;
	display: flex;
	align-items: center;
	gap: 6px;
	height: 32px;
	padding: 0 14px;
	border-radius: 6px;
	border: 1.5px solid ${a.VG};
	background: var(--etn-surface, #ffffff);
	color: var(--etn-primary-text, ${a.VG});
	font-size: 13px;
	font-weight: 500;
	cursor: default;
`,d=l.A.button`
	flex-shrink: 0;
	display: flex;
	align-items: center;
	height: 32px;
	padding: 0 14px;
	border-radius: 6px;
	border: none;
	background: ${a.VG};
	color: #ffffff;
	font-size: 13px;
	font-weight: 500;
	cursor: pointer;

	&:hover {
		opacity: 0.9;
	}
`,c=l.A.div`
	background: var(--etn-surface, #ffffff);
	border-radius: 6px;
	box-shadow: 0 4px 12px rgba( 0, 0, 0, 0.1 );
	padding: 4px;
	min-width: 120px;
`,p=l.A.div`
	display: flex;
	align-items: center;
	gap: 8px;
	padding: 8px 12px;
	border-radius: 4px;
	color: ${({danger:e})=>e?"var(--etn-danger, #ef4444)":"var(--etn-text-secondary, #374151)"};
	font-size: 14px;
	font-weight: 400;
	cursor: pointer;

	&:hover {
		background: ${({danger:e})=>e?"var(--etn-danger-bg, #fff1f1)":"var(--etn-bg-subtle, #f3f4f6)"};
	}

	.anticon {
		font-size: 15px;
	}
`;n.d(t,["JY",0,o,"Po",0,d,"Ql",0,s,"YM",0,r,"c7",0,i,"cb",0,c,"eV",0,p])},71133(e,t,n){var a=n(51609),l=n(86087),i=n(27723),r=n(40372),o=n(11804),s=n(54725),d=n(64074);const{useBreakpoint:c}=r.Ay;n.d(t,["s",0,({value:e="event",onChange:t})=>{const n=!c()?.md,r=(0,l.useMemo)(()=>[{label:(0,a.createElement)("span",{className:"button-title"},(0,a.createElement)(s.GKP,{width:16,height:16})," ",(0,i.__)("Event Landing","eventin")),value:"event"},{label:(0,a.createElement)("span",{className:"button-title"},(0,a.createElement)(s.qyI,{width:16,height:16})," ",(0,i.__)("Tickets","eventin")),value:"ticket"},{label:(0,a.createElement)("span",{className:"button-title"},(0,a.createElement)(s.j$V,{width:16,height:16})," ",(0,i.__)("Certificate","eventin")),value:"certificate"},{label:(0,a.createElement)("span",{className:"button-title"},(0,a.createElement)(s.pD_,{width:16,height:16})," ",(0,i.__)("Speaker","eventin")),value:"speaker"}],[]);return(0,a.createElement)(d.P,null,(0,a.createElement)(o.A,{options:r,value:e,size:"large",onChange:t,vertical:!!n}))}])},64074(e,t,n){const a=n(69815).A.div`
	.ant-segmented {
		padding: 10px;
		border-radius: 6px;
		width: 100%;

		.ant-segmented-item {
			border: 1px solid transparent;
			border-radius: 4px;
			padding: 4px 20px;
			font-weight: 500;
		}
		.ant-segmented-item-selected {
			color: var(--etn-primary-text, #6b2ee5);
			align-items: center;
		}
	}
	.ant-segmented-item-label {
		min-height: 50px;
		line-height: 53px;
		padding: 0 18px;
		font-size: 16px !important;
		font-weight: 600;
	}

	.button-title {
		display: flex;
		align-items: center;
		gap: 8px;
	}
`;n.d(t,["P",0,a])},5100(e,t,n){var a=n(51609),l=n(27723),i=n(54725);const r="undefined"!=typeof window&&Boolean(window.localized_data_obj?.bricks_supported),o=[{key:"gutenberg",title:(0,l.__)("Block Editor","eventin"),icon:(0,a.createElement)(i.tEo,null)},{key:"elementor",title:(0,l.__)("Elementor","eventin"),icon:(0,a.createElement)(i.VUz,null)},...r?[{key:"bricks",title:(0,l.__)("Bricks","eventin"),icon:(0,a.createElement)(i.GYo,null)}]:[]];n.d(t,["m",0,o,"t",0,"https://themewinter.com/docs/plugins/plugin-docs/template-builder/how-to-create-event-templates/"])},38693(e,t,n){var a=n(51609),l=n(27723),i=n(92911),r=n(54725),o=n(72725),s=n(5100),d=n(80624),c=n(74871);n.d(t,["A",0,({installResponse:e,setInstallResponse:t,selectedEditor:n,setSelectedEditor:p,builderLists:m,builderLoading:u})=>(0,a.createElement)(c.d4,null,(0,a.createElement)(i.A,{justify:"center",gap:10},s.m.map(e=>(0,a.createElement)(c.$w,{key:e.key,active:n===e.key,onClick:()=>(async e=>{p(e)})(e.key)},n===e.key&&(0,a.createElement)("span",{className:"eve-svg-wrapper"},(0,a.createElement)(r.nP2,null)),e.icon,(0,a.createElement)("h4",null,e.title)))),(0,o.P)(m,n)&&n||"gutenberg"===n||e?.is_active||!n?(0,a.createElement)("p",{className:"eve-editor-list"},(0,l.__)("Please choose your preferred page builder from the list so you will only see templates that are made using that page builder.","eventin"),(0,a.createElement)("a",{className:"eve-link",href:s.t,target:"_blank"},(0,l.__)(" learn More","eventin"))):(0,a.createElement)(d.c,{installResponse:e,setInstallResponse:t,selectedEditor:n}))])},80624(e,t,n){var a=n(51609),l=n(86087),i=n(27723),r=n(7638),o=n(64282),s=n(74871);n.d(t,["c",0,({setInstallResponse:e,selectedEditor:t})=>{const[n,d]=(0,l.useState)(!1);return(0,a.createElement)(s.Gj,null,(0,a.createElement)("h3",null,(0,i.__)("It seems that the page builder you selected is inactive.","eventin")),(0,a.createElement)("p",null,(0,i.__)("By selecting Elementor, you can edit all Event, Certificate, and Ticket templates with ease. Create your own designs using both Eventin’s widgets and Elementor’s widgets easily.","eventin")),(0,a.createElement)(r.Ay,{variant:r.Vt,onClick:async()=>{d(!0);try{const n=await o.A.template.activeSelectedEditor({builder_id:t});return e(n),n}catch(e){console.log(e)}finally{d(!1)}},loading:n},(0,i.__)("Install & Active","eventin")))}])},74871(e,t,n){var a=n(69815);const l=a.A.div`
	.eve-editor-list {
		font-size: 14px;
		line-height: 18px;
		color: var(--etn-text-secondary, #454545);

		.eve-link {
			color: var(--etn-primary-text, #6b2ee5);
			font-weight: 500;
			cursor: pointer;
		}
	}
`,i=a.A.div`
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	min-width: 120px;
	min-height: 120px;
	border-radius: 6px;
	border: 1px solid ${({active:e})=>e?"#6B2EE5":"var(--etn-border, #F0F0F0)"};
	background: ${({active:e})=>e?"var(--etn-primary-bg, transparent)":"var(--etn-bg-subtle, transparent)"};
	position: relative;
	cursor: pointer;
	transition: border-color 0.15s ease;

	&:hover {
		border-color: ${({active:e})=>e?"#6B2EE5":"var(--etn-primary-border, #F0F0F0)"};
	}
	h4 {
		color: var(--etn-text-secondary, #334155);
		font-size: 14px;
		font-weight: 500;
		line-height: 16px;
		margin: 12px 0px 0px 0px;
	}

	.eve-svg-wrapper {
		position: absolute;
		top: 8px;
		right: 8px;
	}
`,r=a.A.div`
	background-color: var(--etn-bg-subtle, #f5f5f5);
	padding: 20px;
	border-radius: 6px;
	margin-top: 12px;

	h3 {
		font-weight: 600;
		font-size: 18px;
		color: var(--etn-text-secondary, #454545);
		margin: 0px;
	}
	p {
		font-size: 14px;
		line-height: 18px;
		color: var(--etn-text-secondary, #454545);
	}
`;n.d(t,["$w",0,i,"Gj",0,r,"d4",0,l])},28631(e,t,n){var a=n(86087),l=n(64282);n.d(t,["f",0,()=>{const[e,t]=(0,a.useState)([]),[n,i]=(0,a.useState)(!1);return{getAllActiveTemplateBuilders:async()=>{i(!0);try{const e=await l.A.template.getActiveTemplateBuilders();return t(e),e}catch(e){console.log(e)}finally{i(!1)}},builderLists:e,builderLoading:n}}])},42670(e,t,n){var a=n(51609),l=n(86087),i=n(27723),r=n(75093),o=n(72725),s=n(38693),d=n(28631),c=n(9765);n.d(t,["A",0,({selectedEditor:e,setSelectedEditor:t,openSelectEditorModal:n,setOpenSelectEditorModal:p,onEditorApplied:m})=>{const[u,g]=(0,l.useState)(null),{getAllActiveTemplateBuilders:v,builderLists:b,builderLoading:f}=(0,d.f)();return(0,l.useEffect)(()=>{v()},[]),(0,a.createElement)(r.aF,{open:n,onCancel:()=>p(!1),footer:!!((0,o.P)(b,e)&&e||"gutenberg"===e||u?.is_active)&&(0,a.createElement)(c.A,{selectedEditor:e,setOpenSelectEditorModal:p,onEditorApplied:m}),width:"670px",destroyOnHidden:!0,wrapClassName:"etn-template-create-modal",title:(0,i.__)("Choose a Page Builder to Continue","eventin")},(0,a.createElement)(s.A,{builderLists:b,builderLoading:f,installResponse:u,setInstallResponse:g,selectedEditor:e,setSelectedEditor:t}))}])},9765(e,t,n){var a=n(51609),l=n(86087),i=n(27723),r=n(7638),o=n(64282),s=n(92911);n.d(t,["A",0,({selectedEditor:e,setOpenSelectEditorModal:t,onEditorApplied:n})=>{const[d,c]=(0,l.useState)(!1);return(0,a.createElement)(s.A,{gap:12,justify:"flex-end"},(0,a.createElement)(r.Ay,{variant:r.Vt,onClick:()=>t(!1)},(0,i.__)("Cancel","eventin")),(0,a.createElement)(r.Ay,{variant:r.zB,onClick:async()=>{try{c(!0);const a=await o.A.settings.updateSettings({selected_template_builder:e});a.selected_template_builder&&(t(!1),n?.(a.selected_template_builder))}catch(e){console.log(e)}finally{c(!1)}},loading:d},(0,i.__)("Apply Template","eventin")))}])},97914(e,t,n){var a=n(51609),l=n(27723),i=n(428),r=n(54725),o=n(75093),s=n(9312),d=n(58892);const c=!!window.localized_data_obj?.evnetin_pro_active;n.d(t,["A",0,({templateType:e,templateLists:t,createBlankTemplate:n,loadingBlankTemplate:p})=>{const m=!c&&"event"!==e||!c&&1===t.length,u=s.Sc[e]||"";return(0,a.createElement)(d.Uj,{isLandingTemplate:"event"===e,onClick:m?void 0:n,disabled:m,onKeyDown:e=>!m&&"Enter"===e.key&&n()},(0,a.createElement)(i.A,{spinning:p},m?(0,a.createElement)(o.Oc,null):(0,a.createElement)(a.Fragment,null,(0,a.createElement)(r.z6Y,{width:36,height:36}),(0,a.createElement)("p",null,(0,l.__)("Create your own","eventin")," ",(0,a.createElement)("br",null)," ",u))))}])},17827(e,t,n){var a=n(51609),l=n(27723),i=n(54725),r=n(77906),o=n(11721),s=n(58892);n.d(t,["A",0,({builderFilter:e,setBuilderFilter:t,dropdownOpen:n,setDropdownOpen:d,isElementorActive:c})=>{const p=Boolean(window?.localized_data_obj?.bricks_supported),m=[{key:"gutenberg",label:(0,l.__)("Gutenberg","eventin"),icon:(0,a.createElement)(i.tEo,{width:20,height:20}),installed:!0},{key:"elementor",label:(0,l.__)("Elementor","eventin"),icon:(0,a.createElement)(i.VUz,{width:20,height:20}),installed:c},...p?[{key:"bricks",label:(0,l.__)("Bricks","eventin"),icon:(0,a.createElement)(i.GYo,{width:20,height:20}),installed:!0}]:[]],u=m.find(t=>t.key===e),g=(0,a.createElement)(s.C$,null,m.map(n=>(0,a.createElement)(s.uX,{key:n.key,isActive:e===n.key,disabled:!n.installed,onClick:()=>{n.installed&&(t(n.key),d(!1))}},n.icon,(0,a.createElement)("span",{className:"builder-label"},n.label),!n.installed&&(0,a.createElement)("span",{className:"not-installed-badge"},(0,l.__)("Not installed","eventin")),e===n.key&&(0,a.createElement)(r.A,{className:"check-icon"}))));return(0,a.createElement)(o.A,{popupRender:()=>g,trigger:["click"],placement:"bottomRight",open:n,onOpenChange:d,getPopupContainer:()=>document.querySelector(".etn-template-create-modal")||document.body,zIndex:1100},(0,a.createElement)(s.AY,null,u?.icon,u?.label))}])},15358(e,t,n){var a=n(51609),l=n(27723),i=n(75093),r=n(9312),o=n(58892);const s={height:40,width:150,textAlign:"center",border:"1px solid #6B2EE5",color:"var(--etn-primary-text, #6B2EE5)",fontSize:14,fontWeight:500,padding:"0px 16px",backgroundColor:"var(--etn-surface, white)",cursor:"pointer","&:hover":{backgroundColor:"#6B2EE5 !important",color:"white !important"}},d=!!window.localized_data_obj?.evnetin_pro_active;n.d(t,["A",0,({template:e,templateType:t,templateLists:n,useTemplate:c,loadingUseTemplate:p,isBuilderMismatch:m})=>{const u=!d&&"event"!==t||!d&&1===n.length;return(0,a.createElement)(o.xW,{isLandingTemplate:"event"===t},"event"===t&&e.isStatic&&(0,a.createElement)(o.Hf,null,(0,l.__)("Default","eventin")),(0,a.createElement)(o.iX,{src:e.thumbnail||r.cf[t]}),(0,a.createElement)("div",{className:"template-name-overlay"},(0,a.createElement)("div",{className:"etn-template-card-action"},u?(0,a.createElement)(i.Oc,null):(0,a.createElement)(a.Fragment,null,!m&&(0,a.createElement)(i.$n,{onClick:()=>c(e,e.isStatic),loading:p[e.id]||!1,disabled:e.isStatic&&e.isAdded,sx:s},e.isStatic?e.isAdded?(0,l.__)("Already Added","eventin"):(0,l.__)("Add to List","eventin"):(0,l.__)("Use this template","eventin")),(0,a.createElement)(i.$n,{onClick:()=>window.open(e.preview_link,"_blank"),sx:s},(0,l.__)("Preview now","eventin"))))))}])},16912(e,t,n){var a=n(51609),l=n(27723),i=n(16133);const r={gutenberg:(0,l.__)("Gutenberg","eventin"),elementor:(0,l.__)("Elementor","eventin")},o={event:(0,l.__)("Event Landing","eventin"),ticket:(0,l.__)("Ticket","eventin"),certificate:(0,l.__)("Certificate","eventin"),speaker:(0,l.__)("Speaker","eventin")};n.d(t,["A",0,({templateType:e,builderFilter:t})=>{const n=o[e]||e,s=r[t]||t,d="speaker"===e?(0,l.__)("No Speaker templates found.","eventin"):
// translators: 1: template type label, 2: builder name
// translators: 1: template type label, 2: builder name
(0,l.sprintf)(/* translators: 1: template type (e.g. "Event Landing"), 2: builder (e.g. "Elementor") */ /* translators: 1: template type (e.g. "Event Landing"), 2: builder (e.g. "Elementor") */
(0,l.__)("No %1$s templates available for %2$s. Try switching the builder filter.","eventin"),n,s);return(0,a.createElement)(i.A,{description:d,style:{marginTop:"40px",width:"100%"}})}])},1104(e,t,n){var a=n(51609),l=n(16370),i=n(75063);const r={speaker:4,default:2};n.d(t,["A",0,({templateType:e})=>{var t;const n=null!==(t=r[e])&&void 0!==t?t:r.default,o="event"===e?460:280;return Array.from({length:n}).map((e,t)=>(0,a.createElement)(l.A,{key:t},(0,a.createElement)(i.A.Node,{active:!0,style:{width:380,height:o}})))}])},47079(e,t,n){var a=n(51609),l=n(29491),i=n(47143),r=n(27723),o=n(71133),s=n(82654),d=n(58892),c=n(17827),p=n(51572),m=n(15291);const u=(0,i.withDispatch)(e=>({setShowCreateTemplateModal:e("eventin/global").setShowCreateTemplateModal,invalidateTemplateList:()=>e("eventin/global").invalidateResolution("getTemplateList")})),g=(0,i.withSelect)(e=>({showCreateTemplateModal:e("eventin/global").getShowCreateTemplateModal(),templateLists:e("eventin/global").getTemplateList()})),v=(0,l.compose)([g,u])(function(e){const{templateType:t,showCreateTemplateModal:n,setShowCreateTemplateModal:l,invalidateTemplateList:i,selectedEditor:u,templateLists:g}=e||{},{isProActive:v,isElementorActive:b,templateType:f,setTemplateType:h,dropdownOpen:y,setDropdownOpen:x,builderFilter:E,setBuilderFilter:_,loadingBlankTemplate:w,loadingUseTemplate:k,templateLoading:A,displayedTemplates:T,hasMore:S,loadMoreEvents:C,isBuilderMismatch:L,createBlankTemplate:I,useTemplate:N,previewTemplate:z}=(0,m.A)({selectedTemplateType:t,selectedEditor:u,setShowCreateTemplateModal:l,invalidateTemplateList:i});return(0,a.createElement)(d.vq,{open:n,onCancel:()=>l(!1),footer:!1,width:"65vw",destroyOnHidden:!0,wrapClassName:"etn-template-create-modal",title:(0,r.__)("Choose a template","eventin")},(0,a.createElement)("div",{className:"etn-template-view-wrapper"},(0,a.createElement)("div",{className:"etn-template-header"},(0,a.createElement)(o.s,{value:f,onChange:h}),(0,a.createElement)(c.A,{builderFilter:E,setBuilderFilter:_,dropdownOpen:y,setDropdownOpen:x,isElementorActive:b})),!v&&Array.isArray(g)&&1===g?.filter(e=>!e.isStatic).length&&(0,a.createElement)(s.A,{message:(0,r.__)("Upgrade to Eventin Pro to create unlimited templates!","eventin"),type:"warning",style:{marginBottom:"12px",width:"fit-content"}}),(0,a.createElement)("div",{className:"etn-template-view-content"},(0,a.createElement)(p.A,{templateLists:g,templates:T,templateType:f,builderFilter:E,createBlankTemplate:I,useTemplate:N,previewTemplate:z,templateLoading:A,loadingBlankTemplate:w,loadingUseTemplate:k,isBuilderMismatch:L,hasMore:S,onLoadMore:C}))))});n.d(t,["A",0,v])},58892(e,t,n){var a=n(69815),l=n(19549);const i=(0,a.A)(l.A)`
	.ant-modal-content {
		padding: 0;
		border-radius: 8px;

		.ant-modal-header {
			margin: 0;
		}

		.ant-modal-title {
			padding: 26px 24px;
			font-size: 26px;
			font-weight: 600;
			line-height: 1;
			background-color: var(--etn-surface, #ffffff);
			border-radius: 8px 8px 0 0;
		}

		.ant-modal-body {
			padding: 20px 24px 22px;
			background-color: var(--etn-bg-subtle, #f3f5f7);
			border-radius: 0 0 8px 8px;
			img {
				display: block;
				width: 100%;
			}
		}
	}
	.etn-template-header {
		display: flex;
		align-items: center;
		justify-content: space-between;
		margin-bottom: 15px;
		border-radius: 4px 4px 0 0;
		border-bottom: 1px solid var(--etn-border, #ddd);
		padding-bottom: 4px;
	}

	.etn-template-view-wrapper {
		width: 100%;
		height: 70vh;
		display: flex;
		flex-direction: column;
		overflow: hidden;
	}

	.etn-template-view-content {
		flex: 1;
		overflow-y: auto;
		overflow-x: hidden;
		padding-bottom: 8px;
	}
`,r=a.A.div`
	border: 1px solid var(--etn-border-subtle, rgba( 0, 0, 0, 0.06 ));
	padding: 0px;
	border-radius: 6px;
	width: ${({isLandingTemplate:e})=>e?"380px":"372px"};
	height: ${({isLandingTemplate:e})=>e?"460px":"280px"};
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	background-color: var(--etn-bg-subtle, #f5f5f5);
	border-radius: 8px;
	border: 1px solid var(--etn-border-subtle, rgba( 0, 0, 0, 0.06 ));

	&:hover {
		cursor: pointer;
		background-color: #170a3233;
	}

	.anticon {
		margin-bottom: 10px;
		display: flex;
		justify-content: center;
		width: 100%;
		color: var(--etn-primary-text, #6b2ee5);
	}

	p {
		font-size: 14px;
		font-weight: 600;
		line-height: 22px;
		margin: 0;
		text-align: center;
	}
`,o=a.A.div`
	position: relative;
	overflow: hidden;
	width: ${({isLandingTemplate:e})=>e?"380px":"372px"};
	height: ${({isLandingTemplate:e})=>e?"460px":"280px"};
	background-color: var(--etn-surface, #fff);
	border-radius: 8px;
	border: 1px solid var(--etn-border-subtle, rgba( 0, 0, 0, 0.06 ));
	.template-name-overlay {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		padding: 8px;
		background-color: #170a3233;
		opacity: 0;
		transition: opacity 0.2s;
	}
	.etn-template-card-action {
		gap: 10px;
		align-items: center;
		justify-content: center;
		flex-direction: column;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate( -50%, -50% );
		display: none;
		opacity: 0;
	}

	&:hover {
		.template-name-overlay {
			opacity: 1;
		}
		.etn-template-card-action {
			display: flex;
			opacity: 1;
		}
	}
`,s=a.A.div`
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-image: url( ${({src:e})=>e} );
	background-size: cover;
	background-position: top center;
	background-repeat: no-repeat;
`,d=a.A.button`
	display: inline-flex;
	align-items: center;
	gap: 8px;
	height: 36px;
	padding: 0 12px;
	border-radius: 6px;
	border: 1px solid var(--etn-border, #dce3ec);
	background: var(--etn-surface, #ffffff);
	cursor: pointer;
	font-size: 13px;
	font-weight: 500;
	color: var(--etn-text-secondary, #374151);
	white-space: nowrap;
	transition: border-color 0.2s;

	&:hover {
		border-color: #6b2ee5;
	}

	svg {
		flex-shrink: 0;
	}
`,c=a.A.div`
	min-width: 200px;
	background: var(--etn-surface, #ffffff);
	border-radius: 8px;
	box-shadow: 0 6px 24px rgba( 0, 0, 0, 0.12 );
	padding: 6px 0;
	overflow: hidden;
`,p=a.A.button`
	width: 100%;
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 9px 14px;
	border: none;
	background: ${({isActive:e})=>e?"var(--etn-primary-bg, #f5f0ff)":"transparent"};
	cursor: ${({disabled:e})=>e?"not-allowed":"pointer"};
	font-size: 13px;
	font-weight: 500;
	color: ${({disabled:e})=>e?"var(--etn-text-disabled, #9ca3af)":"var(--etn-text, #111827)"};
	text-align: left;
	transition: background 0.15s;

	&:hover {
		background: ${({disabled:e,isActive:t})=>e?"transparent":t?"var(--etn-primary-bg, #ede8ff)":"var(--etn-bg-subtle, #f9fafb)"};
	}

	.builder-label {
		flex: 1;
	}

	.not-installed-badge {
		font-size: 11px;
		font-weight: 500;
		color: var(--etn-warning, #d97706);
		background: var(--etn-warning-bg, #fef3c7);
		border: 1px solid #fde68a;
		border-radius: 100px;
		padding: 1px 7px;
	}

	.check-icon {
		color: var(--etn-primary-text, #6b2ee5);
		font-size: 14px;
	}
`,m=a.A.span`
	position: absolute;
	top: 12px;
	right: 12px;
	z-index: 10;
	background-color: #6b2ee5;
	color: #ffffff;
	font-size: 13px;
	font-weight: 600;
	line-height: 1;
	padding: 8px 20px;
	border-radius: 100px;
	white-space: nowrap;
	pointer-events: none;
`;n.d(t,["AY",0,d,"C$",0,c,"Hf",0,m,"Uj",0,r,"iX",0,s,"uX",0,p,"vq",0,i,"xW",0,o])},51572(e,t,n){var a=n(51609),l=n(16370),i=n(47152),r=n(70940),o=n(97914),s=n(15358),d=n(16912),c=n(1104);n.d(t,["A",0,e=>{const{templateLists:t,templates:n,templateType:p,builderFilter:m,createBlankTemplate:u,useTemplate:g,templateLoading:v,loadingBlankTemplate:b,loadingUseTemplate:f,isBuilderMismatch:h,hasMore:y,onLoadMore:x}=e||{};return(0,a.createElement)(i.A,{gutter:[16,16]},"speaker"!==p&&!v&&(0,a.createElement)(l.A,null,(0,a.createElement)(o.A,{templateType:p,templateLists:t,createBlankTemplate:u,loadingBlankTemplate:b})),v?(0,a.createElement)(c.A,{templateType:p}):n?.length?n?.map(e=>(0,a.createElement)(l.A,{key:e.id},(0,a.createElement)(s.A,{template:e,templateType:p,templateLists:t,useTemplate:g,loadingUseTemplate:f,isBuilderMismatch:h}))):"speaker"===p?(0,a.createElement)(l.A,{span:24},(0,a.createElement)(d.A,{templateType:p,builderFilter:m})):null,(0,a.createElement)(l.A,{span:24},(0,a.createElement)(r.A,{hasMore:y,onLoadMore:x,scrollContainerSelector:".etn-template-view-content"})))}])},15291(e,t,n){var a=n(86087),l=n(52619),i=n(57933),r=n(64282),o=n(9312),s=n(85496);n.d(t,["A",0,({selectedTemplateType:e,selectedEditor:t,setShowCreateTemplateModal:n,invalidateTemplateList:d})=>{const c=!!window.localized_data_obj?.evnetin_pro_active,p=!!window.localized_data_obj?.elementor_supported,m=()=>t||window?.localized_data_obj?.selected_template_builder||"gutenberg",[u,g]=(0,a.useState)(e||"event"),[v,b]=(0,a.useState)(!1),[f,h]=(0,a.useState)(m),[y,x]=(0,a.useState)(!1),[E,_]=(0,a.useState)({});(0,a.useEffect)(()=>{g(e||"event")},[e]),(0,a.useEffect)(()=>{h(m())},[t]);const w=({event:[],certificate:[],ticket:[],speaker:[]}[u]||[]).filter(e=>e?.template_builder===f),k=f!==t,A="event"===u?w:[],{displayedItems:T,hasMore:S,loadMore:C}=(0,i.XD)({items:A,pageSize:6,resetDeps:[u,f]});return{isProActive:c,isElementorActive:p,templateType:u,setTemplateType:g,dropdownOpen:v,setDropdownOpen:b,builderFilter:f,setBuilderFilter:h,loadingBlankTemplate:y,loadingUseTemplate:E,templateLoading:!1,displayedTemplates:"event"===u?T:w,hasMore:S,loadMoreEvents:C,isBuilderMismatch:k,createBlankTemplate:async()=>{x(!0);try{const e=await(0,s.i)({templateType:u,builderFilter:f});n(!1),d();const t=e?.edit_link;t&&window.open(`${t}`,"_blank")}catch(e){console.error("Error creating template:",e),(0,l.doAction)("eventin_notification",{type:"error",message:e.message}),n(!1)}finally{x(!1)}},useTemplate:async(e,t)=>{_(t=>({...t,[e.id]:!0}));try{if(t){const t=await r.A.template.createTemplate({id:e.id,is_static:!0,name:e.name||`New ${e.type} template`,orientation:e.orientation||"landscape",content:e.content||"",status:"draft",type:e.type,thumbnail:e.thumbnail||""});n(!1),d(),t?.success&&(0,l.doAction)("eventin_notification",{type:"success",message:t?.message||__("Template added to list successfully","eventin")})}else{const t=await r.A.template.createTemplate({name:e.name||`New ${e.type} template`,orientation:e.orientation||"landscape",content:e.content||"",status:"draft",type:e.type,thumbnail:e.thumbnail||"",template_builder:f});n(!1),d();const a=t?.edit_link;a&&window.open(`${a}`,"_blank")}}catch(e){console.error("Error using template:",e),(0,l.doAction)("eventin_notification",{type:"error",message:e.message}),n(!1)}finally{_(t=>({...t,[e.id]:!1}))}},previewTemplate:e=>{window.open(`${o.m2}${e}`,"_blank")}}}])},57922(e,t,n){var a=n(86087);n.d(t,["A",0,e=>{const[t,n]=(0,a.useState)({event:null,ticket:null,certificate:null,speaker:null}),l=(0,a.useCallback)((e,t)=>{n(n=>({...n,[e]:{...t}}))},[]),i=(0,a.useCallback)(e=>t[e],[t]);return(0,a.useEffect)(()=>{Array.isArray(e)&&e.forEach(e=>{e.is_default&&n(t=>t[e.type]?t:{...t,[e.type]:{...e}})})},[e]),{selectedTemplates:t,selectTemplate:l,getSelectedTemplate:i}}])},30828(e,t,n){n.r(t);var a=n(51609),l=n(29491),i=n(47143),r=n(86087),o=n(52619),s=n(27723),d=n(75093),c=n(64282),p=n(42670),m=n(47079),u=n(85496),g=n(57922),v=n(17437),b=n(6525),f=n(93051),h=n(35293),y=n(86404),x=n(36082),E=n(12236),_=n(77247),w=n(23522);const k=(0,i.withSelect)(e=>{const t=e("eventin/global");return{templateList:t.getTemplateList(),templateListLoading:t.getTemplateListLoading()}}),A=(0,i.withDispatch)(e=>({setShowCreateTemplateModal:e("eventin/global").setShowCreateTemplateModal,invalidateTemplateList:()=>e("eventin/global").invalidateResolution("getTemplateList")})),T=(0,l.compose)([k,A])(e=>{const{setShowCreateTemplateModal:t,templateList:n,templateListLoading:l,invalidateTemplateList:i}=e,[k,A]=(0,r.useState)(window?.localized_data_obj?.selected_template_builder),[T,S]=(0,r.useState)(!1),[C,L]=(0,r.useState)("create"),[I,N]=(0,r.useState)("event"),{selectTemplate:z,getSelectedTemplate:B}=(0,g.A)(n),[$,F]=(0,r.useState)(null),[M,j]=(0,r.useState)(!1),P=(0,r.useRef)(null),D=k||window?.localized_data_obj?.selected_template_builder||"gutenberg",O=e=>(e?.template_builder||"gutenberg")===D,{remoteLoading:R,importingIds:V,importTemplate:U,getVisibleRemoteFor:Y,getStaticTemplatesFor:G,isProActive:H}=(0,w.A)({customTemplates:n||[],invalidateTemplateList:i,onImported:e=>{const t=e?.id;t&&(P.current=String(e?.remote_template_id||""),F(t))}}),X=async e=>{try{j(!0);const t=await(0,u.i)({templateType:I,builderFilter:e}),n=t?.edit_link;if(n){if(t?.id)try{window.sessionStorage.setItem("etn_open_starter",String(t.id))}catch(e){}return void window.open(n,"_blank")}(0,o.doAction)("eventin_notification",{type:"error",message:(0,s.__)("Could not open the template editor.","eventin")})}catch(e){(0,o.doAction)("eventin_notification",{type:"error",message:e?.message||(0,s.__)("Failed to create template.","eventin")})}finally{j(!1)}},q=async()=>{if(!window?.localized_data_obj?.selected_template_builder&&!k)return L("create"),void S(!0);X(D)},K=()=>{L("change"),S(!0)},Q=async e=>{try{const t=await c.A.template.selectEventTemplate({id:e.id,type:e.type});t?.message&&z(e.type,{...e})}catch(e){(0,o.doAction)("eventin_notification",{type:"error",message:e.message})}},W=async e=>{const t=await U(e);t?.id&&await Q({id:t.id,type:e.type})},J=!(n&&n.length>0);return(0,r.useEffect)(()=>{if(!$)return;if(!(n||[]).some(e=>Number(e.id)===Number($)))return;const e=`[data-template-id="${$}"]`,t=document.querySelector(e);t&&"function"==typeof t.scrollIntoView&&(t.scrollIntoView({behavior:"smooth",block:"center"}),t.classList.add("etn-recently-imported"),setTimeout(()=>{t.classList.remove("etn-recently-imported")},3500)),F(null)},[$,n]),l||J&&R?(0,a.createElement)("div",null,(0,a.createElement)(E.A,{title:(0,s.__)("Template Builder","eventin"),buttonText:(0,s.__)("New Template","eventin"),onClickCallback:q,selectedEditor:k,handleOpenEditorSelectModal:K,hideActions:!0}),(0,a.createElement)(b.ff,{className:"eventin-page-wrapper"},(0,a.createElement)(x.g,null,Array.from({length:8}).map((e,t)=>(0,a.createElement)(y.A,{key:t,isEvent:"event"===I}))))):(0,a.createElement)(a.Fragment,null,(0,a.createElement)(v.mL,{styles:b.JO}),(0,a.createElement)(E.A,{title:(0,s.__)("Template Builder","eventin"),buttonText:(0,s.__)("New Template","eventin"),onClickCallback:q,selectedEditor:k,handleOpenEditorSelectModal:K,loading:M}),(0,a.createElement)(b.ff,{className:"eventin-page-wrapper"},(0,a.createElement)(_.A,{activeTab:I,setActiveTab:N,children:{event:(0,a.createElement)(x.g,null,(0,a.createElement)(f.Hf,{inline:!0,templates:[...G("event"),...n.filter(e=>"event"===e.type&&!e.isStatic)].filter(O),onTemplateSelect:Q,selectedTemplateId:B("event")?.id,isLoading:l}),(0,a.createElement)(h.A,{inline:!0,templates:Y("event",D),templateType:"event",isProActive:H,importingIds:V,onImport:W,isLoading:R})),tickets:(0,a.createElement)(x.g,null,(0,a.createElement)(f.bu,{inline:!0,templates:[...G("ticket"),...n.filter(e=>"ticket"===e.type&&!e.isStatic)].filter(O),onTemplateSelect:Q,selectedTemplateId:B("ticket")?.id,isLoading:l}),(0,a.createElement)(h.A,{inline:!0,templates:Y("ticket",D),templateType:"ticket",isProActive:H,importingIds:V,onImport:W,isLoading:R})),certificate:(0,a.createElement)(x.g,null,(0,a.createElement)(f.al,{inline:!0,templates:[...G("certificate"),...n.filter(e=>"certificate"===e.type&&!e.isStatic)].filter(O),onTemplateSelect:Q,selectedTemplateId:B("certificate")?.id,isLoading:l}),(0,a.createElement)(h.A,{inline:!0,templates:Y("certificate",D),templateType:"certificate",isProActive:H,importingIds:V,onImport:W,isLoading:R})),speaker:(0,a.createElement)(x.g,null,(0,a.createElement)(f.Ko,{inline:!0,templates:[...G("speaker"),...n.filter(e=>"speaker"===e.type&&!e.isStatic)].filter(O),onTemplateSelect:Q,selectedTemplateId:B("speaker")?.id,isLoading:l}),(0,a.createElement)(h.A,{inline:!0,templates:Y("speaker",D),templateType:"speaker",isProActive:H,importingIds:V,onImport:W,isLoading:R}))}})),(0,a.createElement)(p.A,{selectedEditor:k,setSelectedEditor:A,openSelectEditorModal:T,setOpenSelectEditorModal:S,onEditorApplied:e=>{A(e),S(!1),"create"===C&&X(e)}}),(0,a.createElement)(m.A,{selectedEditor:k,templateType:I}),(0,a.createElement)(d._W,null))});n.d(t,["default",0,T])},6525(e,t,n){var a=n(69815);const l=a.A.div`
	background-color: var(--etn-bg-page, #f4f6fa);
	padding: 12px 32px;
	min-height: 100vh;
`,i=a.A.button`
	display: inline-flex;
	align-items: center;
	gap: 8px;
	padding: 6px 14px;
	border: 1px solid var(--etn-border, #dce3ec);
	border-radius: 8px;
	background: var(--etn-surface, #fff);
	cursor: pointer;
	font-size: 14px;
	font-weight: 500;
	color: var(--etn-text-secondary, #374151);
	line-height: 1.5;
	transition: border-color 0.2s;

	&:hover {
		border-color: var(--etn-border, #aab4c0);
	}

	.anticon {
		display: flex;
		align-items: center;
	}

	.anticon svg {
		width: 18px;
		height: 18px;
	}
`,r=a.A.button`
	display: inline-flex;
	align-items: center;
	gap: 8px;
	padding: 6px 14px;
	border: 1px solid var(--etn-border, #dce3ec);
	border-radius: 8px;
	background: var(--etn-surface, #fff);
	font-size: 14px;
	font-weight: 500;
	color: var(--etn-text-secondary, #374151);
	cursor: pointer;
	transition: border-color 0.2s;

	&:hover {
		border-color: var(--etn-border, #aab4c0);
	}

	.anticon {
		display: flex;
		align-items: center;
	}

	.anticon svg {
		width: 24px;
		height: 24px;
		border-radius: 100px;
	}
`,o=a.A.div`
	padding: 20px;
	margin-top: -20px;
	.ant-tabs-nav-wrap {
		background-color: var(--etn-surface, #fff);
	}
	.ant-tabs {
		.ant-tabs-tab {
			font-size: 16px;
			font-weight: 500;
			background: transparent;
			color: var(--etn-text, #262626);
			padding: 15px 20px;
		}
		.ant-tabs-content-holder {
			background-color: var(--etn-surface, #ffffff);
			border: 1px solid var(--etn-border, #d9d9d9);
			border-radius: 8px;
			padding: 20px;
		}
		.ant-tabs-tab-active {
			background-color: var(--etn-surface, #ffffff);
			border-bottom: 2px solid var(--etn-border, #d9d9d9);
		}
	}
`;n.d(t,["JO",0,"\n\t@keyframes etnImportedPulse {\n\t\t0% { box-shadow: 0 0 0 0 rgba( 107, 46, 229, 0.85 ); }\n\t\t50% { box-shadow: 0 0 0 6px rgba( 107, 46, 229, 0 ); }\n\t\t100% { box-shadow: 0 0 0 0 rgba( 107, 46, 229, 0 ); }\n\t}\n\t.etn-recently-imported {\n\t\tborder-radius: 8px;\n\t\toutline: 2px solid #6B2EE5;\n\t\tanimation: etnImportedPulse 0.75s ease-in-out 4;\n\t}\n","ff",0,l,"gi",0,o,"sR",0,i,"sX",0,r])},84786(e,t,n){var a=n(51609),l=n(10076),i=n(90455),r=n(86087),o=n(42058),s=n(36082),d=n(86404);n.d(t,["A",0,({templates:e,onTemplateSelect:t,selectedTemplateId:n,isLoading:c=!1,inline:p=!1})=>{const{handleDeleteConfirm:m,handleDuplicate:u}=(0,o.A)(),g=p?r.Fragment:s.g;return c?(0,a.createElement)(g,null,Array.from({length:6}).map((e,t)=>(0,a.createElement)(d.A,{key:t}))):0===e.length?p?null:(0,a.createElement)(i.A,null):(0,a.createElement)(g,null,e.map(e=>(0,a.createElement)("div",{key:e.id,className:"template-card-item","data-template-id":e.id},(0,a.createElement)(l.A,{template:e,templateType:"certificate",selectedTemplateId:n,onTemplateSelect:()=>t(e),handleDeleteConfirm:m,onDuplicate:u}))))}])},84979(e,t,n){var a=n(51609),l=n(10076),i=n(86087),r=n(27723),o=n(16133),s=n(42058),d=n(36082),c=n(86404);n.d(t,["A",0,({templates:e,onTemplateSelect:t,selectedTemplateId:n,isLoading:p=!1,inline:m=!1})=>{const{handleDeleteConfirm:u}=(0,s.A)(),g=m?i.Fragment:d.g;return p?(0,a.createElement)(g,null,Array.from({length:6}).map((e,t)=>(0,a.createElement)(c.A,{key:t,isEvent:!0}))):0===e.length?m?null:(0,a.createElement)(o.A,{description:(0,r.__)("No event templates found","eventin"),style:{marginTop:"40px"}}):(0,a.createElement)(g,null,e.map(e=>(0,a.createElement)("div",{key:e.id,className:"template-card-item","data-template-id":e.id},(0,a.createElement)(l.A,{template:e,selectedTemplateId:n,onTemplateSelect:()=>t(e),handleDeleteConfirm:u}))))}])},44643(e,t,n){var a=n(51609),l=n(27723),i=n(75093),r=n(55392),o=n(22693),s=n(50892),d=n(6836),c=n(9312);n.d(t,["A",0,({template:e,templateType:t,isProActive:n,onImport:p,loading:m})=>{const{thumbnail:u,name:g}=e,v=(window?.localized_data_obj?.eventin_free_template_ids||[]).some(t=>String(t)===String(e.id)),b=!n&&!v,f=u||c.cf[t]||(0,d.QF)("/images/landing_placeholder.webp");return(0,a.createElement)(s.c7,null,(0,a.createElement)(s.YM,{isEventType:"event"===t},(0,a.createElement)("img",{src:f,alt:g,className:"eve-template-img"}),(0,a.createElement)(o.A,{previewLink:e.preview_link,previewLabel:(0,l.__)("Preview","eventin")})),b?(0,a.createElement)(s.JY,null,(0,a.createElement)("p",{className:"title"},g),(0,a.createElement)(i.Oc,null)):(0,a.createElement)(r.A,{name:g,selected:!1,isStatic:!0,menuItems:[],actionLabel:(0,l.__)("Select","eventin"),loading:m,onTemplateSelect:()=>p(e)}))}])},35293(e,t,n){var a=n(51609),l=n(86087),i=n(36082),r=n(86404),o=n(44643);n.d(t,["A",0,({templates:e,templateType:t,isProActive:n,importingIds:s,onImport:d,isLoading:c,inline:p=!1})=>{const m=p?l.Fragment:i.g;return c?(0,a.createElement)(m,null,Array.from({length:6}).map((e,n)=>(0,a.createElement)(r.A,{key:n,isEvent:"event"===t}))):e&&0!==e.length?(0,a.createElement)(m,null,e.map(e=>(0,a.createElement)("div",{key:e.id,className:"template-card-item"},(0,a.createElement)(o.A,{template:e,templateType:t,isProActive:n,onImport:d,loading:!!s[e.id]})))):null}])},84455(e,t,n){var a=n(51609),l=n(10076),i=n(86087),r=n(27723),o=n(16133),s=n(42058),d=n(36082),c=n(86404);n.d(t,["A",0,({templates:e,onTemplateSelect:t,selectedTemplateId:n,isLoading:p=!1,inline:m=!1})=>{const{handleDeleteConfirm:u}=(0,s.A)(),g=m?i.Fragment:d.g;return p?(0,a.createElement)(g,null,Array.from({length:6}).map((e,t)=>(0,a.createElement)(c.A,{key:t}))):0===e.length?m?null:(0,a.createElement)(o.A,{description:(0,r.__)("No speaker templates found","eventin"),style:{marginTop:"40px"}}):(0,a.createElement)(g,null,e.map(e=>(0,a.createElement)("div",{key:e.id,className:"template-card-item","data-template-id":e.id},(0,a.createElement)(l.A,{template:e,templateType:"speaker",selectedTemplateId:n,onTemplateSelect:()=>t(e),handleDeleteConfirm:u}))))}])},86404(e,t,n){var a=n(51609),l=n(69815);const i=l.A.div`
	width: 100%;
	border-radius: 12px;
	border: 2px solid var(--etn-border-subtle, #e5eaf2);
	overflow: hidden;
	background: var(--etn-surface, #ffffff);

	@keyframes etn-skeleton-pulse {
		0% {
			opacity: 1;
		}
		50% {
			opacity: 0.5;
		}
		100% {
			opacity: 1;
		}
	}

	.etn-skeleton-block {
		background-color: var(--etn-bg-muted, #e9edf3);
		animation: etn-skeleton-pulse 1.5s ease-in-out infinite;
	}
`,r=l.A.div`
	height: ${({isEvent:e})=>e?"400px":"280px"};
	width: 100%;
`,o=l.A.div`
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 8px;
	padding: 10px 12px 12px;
`,s=l.A.div`
	height: 12px;
	width: 55%;
	border-radius: 4px;
`,d=l.A.div`
	height: 32px;
	width: 72px;
	border-radius: 6px;
	flex-shrink: 0;
`;n.d(t,["A",0,({isEvent:e=!1})=>(0,a.createElement)(i,null,(0,a.createElement)(r,{className:"etn-skeleton-block",isEvent:e}),(0,a.createElement)(o,null,(0,a.createElement)(s,{className:"etn-skeleton-block"}),(0,a.createElement)(d,{className:"etn-skeleton-block"})))])},40687(e,t,n){var a=n(51609),l=n(10076),i=n(49636),r=n(86087),o=n(42058),s=n(36082),d=n(86404);n.d(t,["A",0,({templates:e,onTemplateSelect:t,selectedTemplateId:n,isLoading:c=!1,inline:p=!1})=>{const{handleDeleteConfirm:m}=(0,o.A)(),u=p?r.Fragment:s.g;return c?(0,a.createElement)(u,null,Array.from({length:6}).map((e,t)=>(0,a.createElement)(d.A,{key:t}))):0===e.length?p?null:(0,a.createElement)(i.A,null):(0,a.createElement)(u,null,e.map(e=>(0,a.createElement)("div",{key:e.id,className:"template-card-item","data-template-id":e.id},(0,a.createElement)(l.A,{template:e,templateType:"ticket",selectedTemplateId:n,onTemplateSelect:()=>t(e),handleDeleteConfirm:m}))))}])},42058(e,t,n){var a=n(47143),l=n(52619),i=n(27723),r=n(80734),o=n(64282);n.d(t,["A",0,()=>{const{setTemplateList:e}=(0,a.useDispatch)("eventin/global"),t=(0,a.useSelect)(e=>e("eventin/global").getTemplateList());return{handleEdit:e=>{window.open(e,"_blank")},handleDeleteConfirm:(n,a)=>{(0,r.A)({title:(0,i.__)("Are you sure?","eventin"),content:(0,i.__)("Are you sure you want to delete this template?","eventin"),onOk:()=>a?(async n=>{const a=t||[];e(a.filter(e=>e.id!==n));try{await o.A.template.deleteStaticTemplate(n),(0,l.doAction)("eventin_notification",{type:"success",message:(0,i.__)("Template removed from list successfully!","eventin")})}catch(t){e(a),(0,l.doAction)("eventin_notification",{type:"error",message:(0,i.__)("Failed to remove template from list!","eventin")})}})(n):(async n=>{const a=t||[];e(a.filter(e=>e.id!==n));try{await o.A.template.deleteTemplate(n),(0,l.doAction)("eventin_notification",{type:"success",message:(0,i.__)("Successfully deleted the template!","eventin")})}catch(t){e(a),(0,l.doAction)("eventin_notification",{type:"error",message:(0,i.__)("Failed to delete the template!","eventin")})}})(n)})},handleDuplicate:async n=>{const a={...n,id:"__optimistic__",name:`${n.name} - ${(0,i.__)("Copy","eventin")}`};e([...t||[],a]);try{const a=await o.A.template.cloneTemplate(n.id),r=await o.A.template.singleTemplate(a.id);await o.A.template.updateTemplate(a.id,{...r,name:`${n.name} - ${(0,i.__)("Copy","eventin")}`}),e([...t||[],{...r,name:`${n.name} - ${(0,i.__)("Copy","eventin")}`}]),(0,l.doAction)("eventin_notification",{type:"success",message:(0,i.__)("Template duplicated successfully!","eventin")})}catch(n){e(t||[]),(0,l.doAction)("eventin_notification",{type:"error",message:(0,i.__)("Failed to duplicate the template!","eventin")})}}}}])},93051(e,t,n){n.d(t,{Hf:()=>a.A,Ko:()=>r.A,al:()=>i.A,bu:()=>l.A});var a=n(84979),l=n(40687),i=n(84786),r=n(84455)},12236(e,t,n){n.d(t,{A:()=>g});var a=n(51609),l=n(56427),i=n(27723),r=n(92911),o=n(32099),s=n(54725),d=n(75093),c=n(27154),p=n(6525);const m={elementor:(0,a.createElement)(s.VUz,null),gutenberg:(0,a.createElement)(s.tEo,null),bricks:(0,a.createElement)(s.GYo,null)};function u({builderKey:e}){var t;return null!==(t=m[e])&&void 0!==t?t:(0,a.createElement)(s.oNH,null)}function g(e){const{title:t,buttonText:n,onClickCallback:m,handleOpenEditorSelectModal:g,selectedEditor:v,hideActions:b=!1,loading:f=!1}=e,h=v||window.localized_data_obj?.selected_template_builder,y=h?h.charAt(0).toUpperCase()+h.slice(1):"";return(0,a.createElement)(l.Fill,{name:c.PQ},(0,a.createElement)(r.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,a.createElement)(d.EJ,{title:t}),!b&&(0,a.createElement)(r.A,{align:"center",gap:8,wrap:"wrap"},h?(0,a.createElement)(o.A,{title:(0,i.__)("Change builder","eventin"),placement:"bottomRight"},(0,a.createElement)(p.sX,{onClick:g},(0,a.createElement)(u,{builderKey:h}),(0,a.createElement)("span",null,y))):(0,a.createElement)(o.A,{title:(0,i.__)("Open builder select","eventin"),placement:"bottomRight"},(0,a.createElement)(p.sR,{onClick:g},(0,a.createElement)(s.oNH,null),(0,a.createElement)("span",null,(0,i.__)("Selected builder","eventin")))),(0,a.createElement)(d.jn,{htmlType:"button",onClick:m,loading:f},(0,a.createElement)(s.bW0,null),n))))}},77247(e,t,n){var a=n(51609),l=n(27723),i=n(80560),r=n(54725),o=n(6525);n.d(t,["A",0,({activeTab:e,setActiveTab:t,children:n})=>{const s=[{key:"event",label:(0,a.createElement)("span",{style:{display:"flex",alignItems:"center",gap:"8px"}},(0,a.createElement)(r.QS2,null),(0,l.__)("Landing page","eventin")),children:n.event},{key:"ticket",label:(0,a.createElement)("span",{style:{display:"flex",alignItems:"center",gap:"8px"}},(0,a.createElement)(r.qyI,null),(0,l.__)("Tickets","eventin")),children:n.tickets},{key:"certificate",label:(0,a.createElement)("span",{style:{display:"flex",alignItems:"center",gap:"8px"}},(0,a.createElement)(r.j$V,null),(0,l.__)("Certificate","eventin")),children:n.certificate},{key:"speaker",label:(0,a.createElement)("span",{style:{display:"flex",alignItems:"center",gap:"8px"}},(0,a.createElement)(r.PPA,null),(0,l.__)("Speaker","eventin")),children:n.speaker}];return(0,a.createElement)(o.gi,null,(0,a.createElement)(i.A,{activeKey:e,onChange:t,items:s,style:{marginTop:"24px"}}))}])},72725(e,t,n){n.d(t,["P",0,(e=[],t)=>{if(!Array.isArray(e)||0===e.length)return!1;const n=e.find(e=>e&&e.id===t);return!!n&&Boolean(n.is_active)}])}}]);