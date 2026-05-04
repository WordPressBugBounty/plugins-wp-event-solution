"use strict";(globalThis.webpackChunkwp_event_solution=globalThis.webpackChunkwp_event_solution||[]).push([[828],{70940(e,t,n){n.d(t,{A:()=>i});var a=n(51609),l=n(86087);const i=({hasMore:e,onLoadMore:t,scrollContainerSelector:n=null})=>{const i=(0,l.useRef)(null);return(0,l.useEffect)(()=>{if(!i.current||!t)return;const a=n?document.querySelector(n):null,l=new IntersectionObserver(n=>{n[0].isIntersecting&&e&&t()},{root:a,threshold:.1});return l.observe(i.current),()=>l.disconnect()},[e,t,n]),e?(0,a.createElement)("div",{ref:i,style:{height:1}}):null}},49636(e,t,n){n.d(t,{A:()=>c});var a=n(51609),l=n(27723),i=n(44100);const r=(0,n(6836).assetURL)("/images/events/ticket-image.webp"),o=[(0,l.__)("Select a ticket template that fits your event","eventin"),(0,l.__)("Customize the layout, colors, and ticket details","eventin"),(0,l.__)("Save & assign the ticket template to your event","eventin")],c=()=>(0,a.createElement)(i.A,{image:r,title:(0,l.__)("Create your event tickets","eventin"),steps:o,docLink:"https://support.themewinter.com/docs/plugins/plugin-docs/template-builder/ticket-template/",docLinkText:(0,l.__)("Learn more","eventin")})},87968(e,t,n){n.d(t,{A:()=>p});var a=n(51609),l=n(69815),i=n(92911),r=n(75063);const o=l.default.div`
	padding: 24px;
	background: white;
	border-radius: 8px;
	box-shadow: 0 1px 3px rgba( 0, 0, 0, 0.1 );
`,c=l.default.div`
	padding: 24px;
	background: white;
`,s=(l.default.div`
	display: flex;
	justify-content: space-between;
	gap: 16px;
	margin-bottom: 24px;
	flex-wrap: wrap;

	@media ( max-width: 768px ) {
		flex-direction: column;
		gap: 24px;

		> div {
			width: 100%;
			justify-content: flex-start;
			flex-wrap: wrap;
		}
	}
`,l.default.div`
	display: grid;
	grid-template-columns: 40px 2fr 1fr 1fr 1fr 1fr 80px;
	padding: 12px 0;
	background: #f9fafb;
	margin-bottom: 8px;
	border-radius: 4px;

	@media ( max-width: 1024px ) {
		grid-template-columns: 40px 2fr 1fr 1fr 1fr 80px;
	}

	@media ( max-width: 768px ) {
		grid-template-columns: 40px 2fr 1fr 1fr 80px;
	}

	@media ( max-width: 576px ) {
		display: none;
	}
`),d=l.default.div`
	display: grid;
	grid-template-columns: 40px 2fr 1fr 1fr 1fr 1fr 80px;
	padding: 16px 0;
	align-items: center;
	border-bottom: 1px solid #f0f0f0;

	@media ( max-width: 1024px ) {
		grid-template-columns: 40px 2fr 1fr 1fr 1fr 80px;
	}

	@media ( max-width: 768px ) {
		grid-template-columns: 40px 2fr 1fr 1fr 80px;
	}

	@media ( max-width: 576px ) {
		grid-template-columns: 20px 1.5fr 1fr 1fr 80px;
		> div:first-of-child {
			display: none;
		}
		> div:nth-of-child( 7 ) {
			display: none;
		}
	}
`,p=()=>(0,a.createElement)(o,null,(0,a.createElement)(c,null,(0,a.createElement)(s,null,(0,a.createElement)("div",null),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:80}}),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:80}}),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:80}}),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:80}}),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:80}}),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:80}})),[1,2,3,4,5,6].map(e=>(0,a.createElement)(d,{key:e},(0,a.createElement)("div",null),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:"80%"}}),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:"80%"}}),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:"80%"}}),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:"80%"}}),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:"80%"}}),(0,a.createElement)(r.A.Button,{active:!0,size:"small",style:{width:"80%"}}))),(0,a.createElement)(i.A,{justify:"space-between",style:{marginTop:24},wrap:"wrap",gap:16},(0,a.createElement)(r.A.Button,{active:!0,style:{width:150}}),(0,a.createElement)(r.A.Button,{active:!0,style:{width:200}}))))},55392(e,t,n){n.d(t,{A:()=>s});var a=n(51609),l=n(27723),i=n(11721),r=n(54725),o=n(7638),c=n(50892);const s=({name:e,selected:t,isStatic:n,onTemplateSelect:s,menuItems:d})=>(0,a.createElement)(c.JY,null,(0,a.createElement)("p",{className:"title"},e),t?(0,a.createElement)(c.Ql,null,(0,a.createElement)(r.SelectedTemplateIcon,{width:16,height:16}),(0,l.__)("Selected","eventin")):(0,a.createElement)(c.Po,{className:"eve-select-btn",onClick:s},(0,l.__)("Select","eventin")),(0,a.createElement)(i.A,{popupRender:()=>(0,a.createElement)(c.cb,null,d.map(e=>(0,a.createElement)(c.eV,{key:e.key,onClick:e.onClick},e.icon,e.label))),trigger:["click"],placement:"bottomRight"},(0,a.createElement)(o.Ay,{sx:{display:"flex",alignItems:"center",gap:"6px",height:"34px",padding:"0 10px",borderRadius:"6px",fontSize:"14px",fontWeight:500,background:"#F3F4F6",border:"1px solid transparent"},variant:o.Vt,className:"etn-template-action-btn"},(0,a.createElement)(r.MoreIconOutlined,null))))},22693(e,t,n){n.d(t,{A:()=>s});var a=n(51609),l=n(27723),i=n(92911),r=n(54725),o=n(7638);const c={display:"flex",alignItems:"center",gap:"6px",height:"36px",padding:"0 16px",borderRadius:"6px",fontSize:"14px",fontWeight:500},s=({previewLink:e,editLink:t})=>(0,a.createElement)(i.A,{className:"template-name-overlay",align:"center",justify:"center",gap:12},(0,a.createElement)(o.Ay,{variant:o.zB,sx:c,onClick:t=>{t.stopPropagation(),window.open(e,"_blank")}},(0,a.createElement)(r.EyeOutlinedIcon,{width:16,height:16}),(0,l.__)("View","eventin")),Boolean(t)&&(0,a.createElement)(o.Ay,{variant:o.Rm,sx:c,onClick:e=>{e.stopPropagation(),window.open(t,"_blank")}},(0,a.createElement)(r.EditOutlined,{width:16,height:16}),(0,l.__)("Edit","eventin")))},10076(e,t,n){n.d(t,{A:()=>d});var a=n(51609),l=n(27723),i=n(54725),r=n(6836),o=n(55392),c=n(22693),s=n(50892);const d=({template:e,selectedTemplateId:t,onTemplateSelect:n,handleDeleteConfirm:d})=>{const{thumbnail:p,isStatic:m,id:u,name:g,edit_link:f,preview_link:v}=e,h=t==u,x=p||(0,r.assetURL)("/images/landing_placeholder.webp"),b=[{label:(0,l.__)("Delete","eventin"),key:"2",icon:(0,a.createElement)(i.DeleteOutlined,{width:"16",height:"16"}),onClick:()=>d(u,m)}];return(0,a.createElement)(s.c7,{isSelected:h},(0,a.createElement)(s.YM,{isCustom:e?.is_custom},(0,a.createElement)("img",{src:x,alt:g,className:"eve-template-img"}),(0,a.createElement)(c.A,{previewLink:v,editLink:f})),(0,a.createElement)(o.A,{name:g,selected:h,isStatic:m,onTemplateSelect:n,menuItems:b}))}},50892(e,t,n){n.d(t,{JY:()=>o,Po:()=>s,Ql:()=>c,YM:()=>r,c7:()=>i,cb:()=>d,eV:()=>p});var a=n(27154),l=n(69815);const i=l.default.div`
  display: flex;
  flex-direction: column;
  border-radius: 8px;
  border: ${({isSelected:e})=>e?`2px solid ${a.PRIMARY_COLOR}`:"2px solid #CBD8EA"};
  overflow: hidden;

  &:hover .eve-select-btn {
    display: flex;
  }
`,r=l.default.div`
  position: relative;
  height: 400px;
  width: 100%;
  background: #f5f5f5 center/cover no-repeat;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;

  &:hover {
    .template-name-overlay {
      opacity: 1;
      visibility: visible;
    }

    &::before {
      content: '';
      background-color: #170a3233;
      position: absolute;
      width: 98%;
      height: 100%;
      border-radius: 4px;
      inset: 3px;
    }
  }

  .eve-template-img {
    width: 100%;
    height: 100%;
    object-fit: ${({isCustom:e})=>e?"contain":"cover"};
    padding: 4px;
    border-radius: 8px;
  }

  .template-name-overlay {
    position: absolute;
    top: 3px;
    left: 3px;
    bottom: 0;
    right: 0;
    width: 98%;
    height: 98%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    opacity: 0;
    transition: all 0.2s;
    visibility: hidden;
  }
`,o=l.default.div`
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 12px 12px;
  gap: 8px;
  background: #ffffff;

  .title {
    margin: 0;
    font-size: 14px;
    font-weight: 400;
    color: #020617;
    flex: 1;
    min-width: 0;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }
`,c=l.default.button`
  flex-shrink: 0;
  display: flex;
  align-items: center;
  gap: 6px;
  height: 32px;
  padding: 0 14px;
  border-radius: 6px;
  border: 1.5px solid ${a.PRIMARY_COLOR};
  background: #ffffff;
  color: ${a.PRIMARY_COLOR};
  font-size: 13px;
  font-weight: 500;
  cursor: default;
`,s=l.default.button`
  flex-shrink: 0;
  display: flex;
  align-items: center;
  height: 32px;
  padding: 0 14px;
  border-radius: 6px;
  border: none;
  background: ${a.PRIMARY_COLOR};
  color: #ffffff;
  font-size: 13px;
  font-weight: 500;
  cursor: pointer;

  &:hover {
    opacity: 0.9;
  }
`,d=l.default.div`
  background: #ffffff;
  border-radius: 6px;
  box-shadow: 0 4px 12px rgba( 0, 0, 0, 0.1 );
  padding: 4px;
  min-width: 120px;
`,p=l.default.div`
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 12px;
  border-radius: 4px;
  color: #ef4444;
  font-size: 14px;
  font-weight: 400;
  cursor: pointer;

  &:hover {
    background: #fff1f1;
  }

  .anticon {
    font-size: 15px;
    color: #ef4444;
  }
`},71133(e,t,n){n.d(t,{s:()=>p});var a=n(51609),l=n(86087),i=n(27723),r=n(40372),o=n(11804),c=n(54725),s=n(64074);const{useBreakpoint:d}=r.Ay,p=({value:e="event",onChange:t})=>{const n=!d()?.md,r=(0,l.useMemo)(()=>[{label:(0,a.createElement)("span",{className:"button-title"},(0,a.createElement)(c.EventTemplateIcon,{width:16,height:16})," ",(0,i.__)("Event Landing","eventin")),value:"event"},{label:(0,a.createElement)("span",{className:"button-title"},(0,a.createElement)(c.TicketIcon,{width:16,height:16})," ",(0,i.__)("Tickets","eventin")),value:"ticket"},{label:(0,a.createElement)("span",{className:"button-title"},(0,a.createElement)(c.CertificateIcon,{width:16,height:16})," ",(0,i.__)("Certificate","eventin")),value:"certificate"},{label:(0,a.createElement)("span",{className:"button-title"},(0,a.createElement)(c.SpeakerIcon,{width:16,height:16})," ",(0,i.__)("Speaker","eventin")),value:"speaker"}],[]);return(0,a.createElement)(s.P,null,(0,a.createElement)(o.A,{options:r,value:e,size:"large",onChange:t,vertical:!!n}))}},64074(e,t,n){n.d(t,{P:()=>a});const a=n(69815).default.div`
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
			color: #6b2ee5;
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
`},5100(e,t,n){n.d(t,{m:()=>r,t:()=>o});var a=n(51609),l=n(27723),i=n(54725);const r=[{key:"gutenberg",title:(0,l.__)("Block Editor","eventin"),icon:(0,a.createElement)(i.WordpressIcon,null)},{key:"elementor",title:(0,l.__)("Elementor","eventin"),icon:(0,a.createElement)(i.ElementorTemplateIcon,null)}],o="https://support.themewinter.com/docs/plugins/plugin-docs/template-builder/how-to-create-event-templates/"},38693(e,t,n){n.d(t,{A:()=>p});var a=n(51609),l=n(27723),i=n(92911),r=n(54725),o=n(72725),c=n(5100),s=n(80624),d=n(74871);const p=({installResponse:e,setInstallResponse:t,selectedEditor:n,setSelectedEditor:p,builderLists:m,builderLoading:u})=>(0,a.createElement)(d.d4,null,(0,a.createElement)(i.A,{justify:"center",gap:10},c.m.map(e=>(0,a.createElement)(d.$w,{key:e.key,active:n===e.key,onClick:()=>(async e=>{p(e)})(e.key)},n===e.key&&(0,a.createElement)("span",{className:"eve-svg-wrapper"},(0,a.createElement)(r.EditorSelectIcon,null)),e.icon,(0,a.createElement)("h4",null,e.title)))),(0,o.P)(m,n)&&n||"gutenberg"===n||e?.is_active||!n?(0,a.createElement)("p",{className:"eve-editor-list"},(0,l.__)("Please choose your preferred page builder from the list so you will only see templates that are made using that page builder.","eventin"),(0,a.createElement)("a",{className:"eve-link",href:c.t,target:"_blank"},(0,l.__)(" learn More","eventin"))):(0,a.createElement)(s.c,{installResponse:e,setInstallResponse:t,selectedEditor:n}))},80624(e,t,n){n.d(t,{c:()=>s});var a=n(51609),l=n(86087),i=n(27723),r=n(7638),o=n(64282),c=n(74871);const s=({setInstallResponse:e,selectedEditor:t})=>{const[n,s]=(0,l.useState)(!1);return(0,a.createElement)(c.Gj,null,(0,a.createElement)("h3",null,(0,i.__)("It seems that the page builder you selected is inactive.","eventin")),(0,a.createElement)("p",null,(0,i.__)("By selecting Elementor, you can edit all Event, Certificate, and Ticket templates with ease. Create your own designs using both Eventin’s widgets and Elementor’s widgets easily.","eventin")),(0,a.createElement)(r.Ay,{variant:r.Vt,onClick:async()=>{s(!0);try{const n=await o.A.template.activeSelectedEditor({builder_id:t});return e(n),n}catch(e){console.log(e)}finally{s(!1)}},loading:n},(0,i.__)("Install & Active","eventin")))}},74871(e,t,n){n.d(t,{$w:()=>i,Gj:()=>r,d4:()=>l});var a=n(69815);const l=a.default.div`
	.eve-editor-list {
		font-size: 14px;
		line-height: 18px;
		color: #454545;

		.eve-link {
			color: #6b2ee5;
			font-weight: 500;
			cursor: pointer;
		}
	}
`,i=a.default.div`
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	min-width: 120px;
	min-height: 120px;
	border-radius: 6px;
	border: 1px solid ${({active:e})=>e?"#6B2EE5":"#F0F0F0"};
	position: relative;
	cursor: pointer;
	h4 {
		color: #334155;
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
`,r=a.default.div`
	background-color: #f5f5f5;
	padding: 20px;
	border-radius: 6px;
	margin-top: 12px;

	h3 {
		font-weight: 600;
		font-size: 18px;
		color: #454545;
		margin: 0px;
	}
	p {
		font-size: 14px;
		line-height: 18px;
		color: #454545;
	}
`},28631(e,t,n){n.d(t,{f:()=>i});var a=n(86087),l=n(64282);const i=()=>{const[e,t]=(0,a.useState)([]),[n,i]=(0,a.useState)(!1);return{getAllActiveTemplateBuilders:async()=>{i(!0);try{const e=await l.A.template.getActiveTemplateBuilders();return t(e),e}catch(e){console.log(e)}finally{i(!1)}},builderLists:e,builderLoading:n}}},42670(e,t,n){n.d(t,{A:()=>p});var a=n(51609),l=n(86087),i=n(27723),r=n(75093),o=n(72725),c=n(38693),s=n(28631),d=n(9765);const p=({selectedEditor:e,setSelectedEditor:t,openSelectEditorModal:n,setOpenSelectEditorModal:p})=>{const[m,u]=(0,l.useState)(null),{getAllActiveTemplateBuilders:g,builderLists:f,builderLoading:v}=(0,s.f)();return(0,l.useEffect)(()=>{g()},[]),(0,a.createElement)(r.Modal,{open:n,onCancel:()=>p(!1),footer:!!((0,o.P)(f,e)&&e||"gutenberg"===e||m?.is_active)&&(0,a.createElement)(d.A,{selectedEditor:e,setOpenSelectEditorModal:p}),width:"670px",destroyOnHidden:!0,wrapClassName:"etn-template-create-modal",title:(0,i.__)("Choose a Page Builder to Continue","eventin")},(0,a.createElement)(c.A,{builderLists:f,builderLoading:v,installResponse:m,setInstallResponse:u,selectedEditor:e,setSelectedEditor:t}))}},9765(e,t,n){n.d(t,{A:()=>m});var a=n(51609),l=n(29491),i=n(47143),r=n(86087),o=n(27723),c=n(7638),s=n(64282),d=n(92911);const p=(0,i.withDispatch)(e=>({setShowCreateTemplateModal:e("eventin/global").setShowCreateTemplateModal})),m=(0,l.compose)([p])(({selectedEditor:e,setOpenSelectEditorModal:t,setShowCreateTemplateModal:n})=>{const[l,i]=(0,r.useState)(!1);return(0,a.createElement)(d.A,{gap:12,justify:"flex-end"},(0,a.createElement)(c.Ay,{variant:c.Vt,onClick:()=>t(!1)},(0,o.__)("Cancel","eventin")),(0,a.createElement)(c.Ay,{variant:c.zB,onClick:async()=>{try{i(!0),(await s.A.settings.updateSettings({selected_template_builder:e})).selected_template_builder&&(t(!1),n(!0))}catch(e){console.log(e)}finally{i(!1)}},loading:l},(0,o.__)("Apply Template","eventin")))})},97914(e,t,n){n.d(t,{A:()=>p});var a=n(51609),l=n(27723),i=n(428),r=n(54725),o=n(75093),c=n(9312),s=n(58892);const d=!!window.localized_data_obj?.evnetin_pro_active,p=({templateType:e,templateLists:t,createBlankTemplate:n,loadingBlankTemplate:p})=>{const m=!d&&"event"!==e||!d&&1===t.length,u=c.Sc[e]||"";return(0,a.createElement)(s.Uj,{isLandingTemplate:"event"===e,onClick:m?void 0:n,disabled:m,onKeyDown:e=>!m&&"Enter"===e.key&&n()},(0,a.createElement)(i.A,{spinning:p},m?(0,a.createElement)(o.ProButton,null):(0,a.createElement)(a.Fragment,null,(0,a.createElement)(r.PlusCircleOutlined,{width:36,height:36}),(0,a.createElement)("p",null,(0,l.__)("Create your own","eventin")," ",(0,a.createElement)("br",null)," ",u))))}},17827(e,t,n){n.d(t,{A:()=>s});var a=n(51609),l=n(27723),i=n(54725),r=n(77906),o=n(11721),c=n(58892);const s=({builderFilter:e,setBuilderFilter:t,dropdownOpen:n,setDropdownOpen:s,isElementorActive:d})=>{const p=[{key:"gutenberg",label:(0,l.__)("Gutenberg","eventin"),icon:(0,a.createElement)(i.WordpressIcon,{width:20,height:20}),installed:!0},{key:"elementor",label:(0,l.__)("Elementor","eventin"),icon:(0,a.createElement)(i.ElementorTemplateIcon,{width:20,height:20}),installed:d}],m=p.find(t=>t.key===e),u=(0,a.createElement)(c.C$,null,p.map(n=>(0,a.createElement)(c.uX,{key:n.key,isActive:e===n.key,disabled:!n.installed,onClick:()=>{n.installed&&(t(n.key),s(!1))}},n.icon,(0,a.createElement)("span",{className:"builder-label"},n.label),!n.installed&&(0,a.createElement)("span",{className:"not-installed-badge"},(0,l.__)("Not installed","eventin")),e===n.key&&(0,a.createElement)(r.A,{className:"check-icon"}))));return(0,a.createElement)(o.A,{popupRender:()=>u,trigger:["click"],placement:"bottomRight",open:n,onOpenChange:s,getPopupContainer:()=>document.querySelector(".etn-template-create-modal")||document.body,zIndex:1100},(0,a.createElement)(c.AY,null,m?.icon,m?.label))}},15358(e,t,n){n.d(t,{A:()=>d});var a=n(51609),l=n(27723),i=n(75093),r=n(9312),o=n(58892);const c={height:40,width:150,textAlign:"center",border:"1px solid #6B2EE5",color:"#6B2EE5",fontSize:14,fontWeight:500,padding:"0px 16px",backgroundColor:"white",cursor:"pointer","&:hover":{backgroundColor:"#6B2EE5 !important",color:"white !important"}},s=!!window.localized_data_obj?.evnetin_pro_active,d=({template:e,templateType:t,templateLists:n,useTemplate:d,loadingUseTemplate:p,isBuilderMismatch:m})=>{const u=!s&&"event"!==t||!s&&1===n.length;return(0,a.createElement)(o.xW,{isLandingTemplate:"event"===t},"event"===t&&e.isStatic&&(0,a.createElement)(o.Hf,null,(0,l.__)("Default","eventin")),(0,a.createElement)(o.iX,{src:e.thumbnail||r.cf[t]}),(0,a.createElement)("div",{className:"template-name-overlay"},(0,a.createElement)("div",{className:"etn-template-card-action"},u?(0,a.createElement)(i.ProButton,null):(0,a.createElement)(a.Fragment,null,!m&&(0,a.createElement)(i.Button,{onClick:()=>d(e,e.isStatic),loading:p[e.id]||!1,disabled:e.isStatic&&e.isAdded,sx:c},e.isStatic?e.isAdded?(0,l.__)("Already Added","eventin"):(0,l.__)("Add to List","eventin"):(0,l.__)("Use this template","eventin")),(0,a.createElement)(i.Button,{onClick:()=>window.open(e.preview_link,"_blank"),sx:c},(0,l.__)("Preview now","eventin"))))))}},16912(e,t,n){n.d(t,{A:()=>c});var a=n(51609),l=n(27723),i=n(16133);const r={gutenberg:(0,l.__)("Gutenberg","eventin"),elementor:(0,l.__)("Elementor","eventin")},o={event:(0,l.__)("Event Landing","eventin"),ticket:(0,l.__)("Ticket","eventin"),certificate:(0,l.__)("Certificate","eventin"),speaker:(0,l.__)("Speaker","eventin")},c=({templateType:e,builderFilter:t})=>{const n=o[e]||e,c=r[t]||t,s="speaker"===e?(0,l.__)("No Speaker templates found.","eventin"):
// translators: 1: template type label, 2: builder name
// translators: 1: template type label, 2: builder name
(0,l.sprintf)(/* translators: 1: template type (e.g. "Event Landing"), 2: builder (e.g. "Elementor") */ /* translators: 1: template type (e.g. "Event Landing"), 2: builder (e.g. "Elementor") */
(0,l.__)("No %1$s templates available for %2$s. Try switching the builder filter.","eventin"),n,c);return(0,a.createElement)(i.A,{description:s,style:{marginTop:"40px",width:"100%"}})}},1104(e,t,n){n.d(t,{A:()=>o});var a=n(51609),l=n(16370),i=n(75063);const r={speaker:4,default:2},o=({templateType:e})=>{var t;const n=null!==(t=r[e])&&void 0!==t?t:r.default,o="event"===e?460:280;return Array.from({length:n}).map((e,t)=>(0,a.createElement)(l.A,{key:t},(0,a.createElement)(i.A.Node,{active:!0,style:{width:380,height:o}})))}},9312(e,t,n){n.d(t,{Sc:()=>s,cf:()=>c,m2:()=>o,s9:()=>r});var a=n(6836),l=n(27723);const i="https://product.themewinter.com/eventin-template",r=`${i}/wp-json/eventin/v2/templates?is_remote=true`,o=`${i}/?action=etn-preview-template&template_id=`,c={certificate:(0,a.assetURL)("/images/event_certificate.webp"),ticket:(0,a.assetURL)("/images/event_ticket.webp")},s={event:(0,l.__)("Event Landing","eventin"),certificate:(0,l.__)("Event Certificate","eventin"),ticket:(0,l.__)("Event Ticket","eventin")}},47079(e,t,n){n.d(t,{A:()=>f});var a=n(51609),l=n(29491),i=n(47143),r=n(27723),o=n(71133),c=n(82654),s=n(58892),d=n(17827),p=n(51572),m=n(15291);const u=(0,i.withDispatch)(e=>({setShowCreateTemplateModal:e("eventin/global").setShowCreateTemplateModal,invalidateTemplateList:()=>e("eventin/global").invalidateResolution("getTemplateList")})),g=(0,i.withSelect)(e=>({showCreateTemplateModal:e("eventin/global").getShowCreateTemplateModal(),templateLists:e("eventin/global").getTemplateList()})),f=(0,l.compose)([g,u])(function(e){const{templateType:t,showCreateTemplateModal:n,setShowCreateTemplateModal:l,invalidateTemplateList:i,selectedEditor:u,templateLists:g}=e||{},{isProActive:f,isElementorActive:v,templateType:h,setTemplateType:x,dropdownOpen:b,setDropdownOpen:E,builderFilter:y,setBuilderFilter:w,loadingBlankTemplate:_,loadingUseTemplate:k,templateLoading:A,displayedTemplates:T,hasMore:C,loadMoreEvents:S,isBuilderMismatch:L,createBlankTemplate:B,useTemplate:I,previewTemplate:z}=(0,m.A)({selectedTemplateType:t,selectedEditor:u,setShowCreateTemplateModal:l,invalidateTemplateList:i});return(0,a.createElement)(s.vq,{open:n,onCancel:()=>l(!1),footer:!1,width:"65vw",destroyOnHidden:!0,wrapClassName:"etn-template-create-modal",title:(0,r.__)("Choose a template","eventin")},(0,a.createElement)("div",{className:"etn-template-view-wrapper"},(0,a.createElement)("div",{className:"etn-template-header"},(0,a.createElement)(o.s,{value:h,onChange:x}),(0,a.createElement)(d.A,{builderFilter:y,setBuilderFilter:w,dropdownOpen:b,setDropdownOpen:E,isElementorActive:v})),!f&&Array.isArray(g)&&1===g?.filter(e=>!e.isStatic).length&&(0,a.createElement)(c.A,{message:(0,r.__)("Upgrade to Eventin Pro to create unlimited templates!","eventin"),type:"warning",style:{marginBottom:"12px",width:"fit-content"}}),(0,a.createElement)("div",{className:"etn-template-view-content"},(0,a.createElement)(p.A,{templateLists:g,templates:T,templateType:h,builderFilter:y,createBlankTemplate:B,useTemplate:I,previewTemplate:z,templateLoading:A,loadingBlankTemplate:_,loadingUseTemplate:k,isBuilderMismatch:L,hasMore:C,onLoadMore:S}))))})},58892(e,t,n){n.d(t,{AY:()=>s,C$:()=>d,Hf:()=>m,Uj:()=>r,iX:()=>c,uX:()=>p,vq:()=>i,xW:()=>o});var a=n(69815),l=n(19549);const i=(0,a.default)(l.A)`
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
			background-color: #ffffff;
			border-radius: 8px 8px 0 0;
		}

		.ant-modal-body {
			padding: 20px 24px 22px;
			background-color: #f3f5f7;
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
		border-bottom: 1px solid #ddd;
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
`,r=a.default.div`
	border: 1px solid rgba( 0, 0, 0, 0.06 );
	padding: 0px;
	border-radius: 6px;
	width: ${({isLandingTemplate:e})=>e?"380px":"372px"};
	height: ${({isLandingTemplate:e})=>e?"460px":"280px"};
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	background-color: #f5f5f5;
	border-radius: 8px;
	border: 1px solid rgba( 0, 0, 0, 0.06 );

	&:hover {
		cursor: pointer;
		background-color: #170a3233;
	}

	.anticon {
		margin-bottom: 10px;
		display: flex;
		justify-content: center;
		width: 100%;
		color: #6b2ee5;
	}

	p {
		font-size: 14px;
		font-weight: 600;
		line-height: 22px;
		margin: 0;
		text-align: center;
	}
`,o=a.default.div`
	position: relative;
	overflow: hidden;
	width: ${({isLandingTemplate:e})=>e?"380px":"372px"};
	height: ${({isLandingTemplate:e})=>e?"460px":"280px"};
	background-color: #fff;
	border-radius: 8px;
	border: 1px solid rgba( 0, 0, 0, 0.06 );
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
`,c=a.default.div`
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background-image: url( ${({src:e})=>e} );
	background-size: cover;
	background-position: top center;
	background-repeat: no-repeat;
`,s=a.default.button`
	display: inline-flex;
	align-items: center;
	gap: 8px;
	height: 36px;
	padding: 0 12px;
	border-radius: 6px;
	border: 1px solid #dce3ec;
	background: #ffffff;
	cursor: pointer;
	font-size: 13px;
	font-weight: 500;
	color: #374151;
	white-space: nowrap;
	transition: border-color 0.2s;

	&:hover {
		border-color: #6b2ee5;
	}

	svg {
		flex-shrink: 0;
	}
`,d=a.default.div`
	min-width: 200px;
	background: #ffffff;
	border-radius: 8px;
	box-shadow: 0 6px 24px rgba( 0, 0, 0, 0.12 );
	padding: 6px 0;
	overflow: hidden;
`,p=a.default.button`
	width: 100%;
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 9px 14px;
	border: none;
	background: ${({isActive:e})=>e?"#f5f0ff":"transparent"};
	cursor: ${({disabled:e})=>e?"not-allowed":"pointer"};
	font-size: 13px;
	font-weight: 500;
	color: ${({disabled:e})=>e?"#9ca3af":"#111827"};
	text-align: left;
	transition: background 0.15s;

	&:hover {
		background: ${({disabled:e,isActive:t})=>e?"transparent":t?"#ede8ff":"#f9fafb"};
	}

	.builder-label {
		flex: 1;
	}

	.not-installed-badge {
		font-size: 11px;
		font-weight: 500;
		color: #d97706;
		background: #fef3c7;
		border: 1px solid #fde68a;
		border-radius: 100px;
		padding: 1px 7px;
	}

	.check-icon {
		color: #6b2ee5;
		font-size: 14px;
	}
`,m=a.default.span`
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
`},51572(e,t,n){n.d(t,{A:()=>p});var a=n(51609),l=n(16370),i=n(47152),r=n(70940),o=n(97914),c=n(15358),s=n(16912),d=n(1104);const p=e=>{const{templateLists:t,templates:n,templateType:p,builderFilter:m,createBlankTemplate:u,useTemplate:g,templateLoading:f,loadingBlankTemplate:v,loadingUseTemplate:h,isBuilderMismatch:x,hasMore:b,onLoadMore:E}=e||{};return(0,a.createElement)(i.A,{gutter:[16,16]},"speaker"!==p&&(f||!!n?.length)&&(0,a.createElement)(l.A,null,(0,a.createElement)(o.A,{templateType:p,templateLists:t,createBlankTemplate:u,loadingBlankTemplate:v})),f?(0,a.createElement)(d.A,{templateType:p}):n?.length?n?.map(e=>(0,a.createElement)(l.A,{key:e.id},(0,a.createElement)(c.A,{template:e,templateType:p,templateLists:t,useTemplate:g,loadingUseTemplate:h,isBuilderMismatch:x}))):(0,a.createElement)(l.A,{span:24},(0,a.createElement)(s.A,{templateType:p,builderFilter:m})),(0,a.createElement)(l.A,{span:24},(0,a.createElement)(r.A,{hasMore:b,onLoadMore:E,scrollContainerSelector:".etn-template-view-content"})))}},15291(e,t,n){n.d(t,{A:()=>c});var a=n(86087),l=n(52619),i=n(57933),r=n(64282),o=n(9312);const c=({selectedTemplateType:e,selectedEditor:t,setShowCreateTemplateModal:n,invalidateTemplateList:c})=>{const s=!!window.localized_data_obj?.evnetin_pro_active,d=!!window.localized_data_obj?.elementor_supported,p=()=>t||window?.localized_data_obj?.selected_template_builder||"gutenberg",[m,u]=(0,a.useState)(e||"event"),[g,f]=(0,a.useState)(!1),[v,h]=(0,a.useState)(p),[x,b]=(0,a.useState)(!1),[E,y]=(0,a.useState)({}),[w,_]=(0,a.useState)(!1),[k,A]=(0,a.useState)({event:[],certificate:[],ticket:[],speaker:[]});(0,a.useEffect)(()=>{u(e||"event")},[e]),(0,a.useEffect)(()=>{h(p())},[t]),(0,a.useEffect)(()=>{(async()=>{try{_(!0);const e=await fetch(o.s9),t=await e.json(),n=Array.isArray(t)?t:[];A({event:n.filter(e=>"event"===e?.type),certificate:n.filter(e=>"certificate"===e?.type),ticket:n.filter(e=>"ticket"===e?.type),speaker:n.filter(e=>"speaker"===e?.type)})}catch(e){console.error("Error fetching templates:",e)}finally{_(!1)}})()},[]);const T=k[m]||[],C=T.filter(e=>e?.template_builder===v),S=C.length>0?C:T,L=v!==t,B="event"===m?S:[],{displayedItems:I,hasMore:z,loadMore:M}=(0,i.useInfiniteScroll)({items:B,pageSize:6,resetDeps:[m,v]});return{isProActive:s,isElementorActive:d,templateType:m,setTemplateType:u,dropdownOpen:g,setDropdownOpen:f,builderFilter:v,setBuilderFilter:h,loadingBlankTemplate:x,loadingUseTemplate:E,templateLoading:w,displayedTemplates:"event"===m?I:S,hasMore:z,loadMoreEvents:M,isBuilderMismatch:L,createBlankTemplate:async()=>{b(!0);let e="\x3c!-- wp:paragraph --\x3e\n<p></p>\n\x3c!-- /wp:paragraph --\x3e";"certificate"!==m&&"ticket"!==m||(e='\x3c!-- wp:eventin-pro/template-container --\x3e\n<div class="wp-block-eventin-pro-template-container"><div><div id="eventin-container-block-wrapper"><div class="etn-downloadable-container" style="border-radius:0px 0px 0px 0px;margin:0px auto 0px auto;padding:20px 20px 20px 20px;background-color:#f3f3f3;min-height:600px;max-width:850px"><div class="eventin-container-block"></div></div></div></div></div>\n\x3c!-- /wp:eventin-pro/template-container --\x3e');try{const t=await r.A.template.createTemplate({name:`New ${m} template`,orientation:"landscape",content:e,status:"draft",type:m,isCustom:!0});n(!1),c();const a=t?.edit_link;a&&window.open(`${a}`,"_blank")}catch(e){console.error("Error creating template:",e),(0,l.doAction)("eventin_notification",{type:"error",message:e.message}),n(!1)}finally{b(!1)}},useTemplate:async(e,t)=>{y(t=>({...t,[e.id]:!0}));try{if(t){const t=await r.A.template.createTemplate({id:e.id,is_static:!0,name:e.name||`New ${e.type} template`,orientation:e.orientation||"landscape",content:e.content||"",status:"draft",type:e.type,thumbnail:e.thumbnail||""});n(!1),c(),t?.success&&(0,l.doAction)("eventin_notification",{type:"success",message:t?.message||__("Template added to list successfully","eventin")})}else{const t=await r.A.template.createTemplate({name:e.name||`New ${e.type} template`,orientation:e.orientation||"landscape",content:e.content||"",status:"draft",type:e.type,thumbnail:e.thumbnail||""});n(!1),c();const a=t?.edit_link;a&&window.open(`${a}`,"_blank")}}catch(e){console.error("Error using template:",e),(0,l.doAction)("eventin_notification",{type:"error",message:e.message}),n(!1)}finally{y(t=>({...t,[e.id]:!1}))}},previewTemplate:e=>{window.open(`${o.m2}${e}`,"_blank")}}}},18448(e,t,n){n.d(t,{W:()=>s});var a=n(6836),l=n(27723);const i=(0,a.assetURL)("/images/events/event-emptypage.webp"),r=(0,a.assetURL)("/images/events/ticket-image.webp"),o=(0,a.assetURL)("/images/events/certificate.webp"),c=[(0,l.__)("Choose a layout for your event.","eventin"),(0,l.__)("Event details  & Customize","eventin"),(0,l.__)("Save & publish your landing page instantly","eventin")],s=[{key:"template",title:(0,l.__)("Landing Page Template","eventin"),lists:c,docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/template-builder/how-to-create-event-templates/",image:i,color:"#874CFC",bgColor:"#EDE9FE"},{key:"tickets",title:(0,l.__)("Tickets Template","eventin"),lists:c,docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/template-builder/template-builder-for-eventin-certificate-and-ticket/",image:r,color:"#3B82F6",bgColor:"#DBEAFE"},{key:"certificates",title:(0,l.__)("Certificates Template","eventin"),lists:c,docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/template-builder/certificate-builder-for-attendee/",image:o,color:"#10B981",bgColor:"#D1FAE5"}]},48290(e,t,n){n.d(t,{A:()=>f});var a=n(51609),l=n(16370),i=n(92911),r=n(47152),o=n(27723),c=n(54725),s=n(75093),d=n(7638),p=n(6525),m=n(18448),u=n(5063);const g={elementor:(0,a.createElement)(c.ElementorTemplateIcon,null),gutenberg:(0,a.createElement)(c.WordpressIcon,null)},f=({onNewTemplate:e,onSelectedBuilder:t,selectedEditor:n})=>{var f;const v=n||window.localized_data_obj?.selected_template_builder,h=v?v.charAt(0).toUpperCase()+v.slice(1):"";return(0,a.createElement)(u.GI,{className:"wrapper"},(0,a.createElement)("h3",{className:"header-title"},(0,o.__)("Template Builder","eventin")),(0,a.createElement)("p",{className:"header-desc"},(0,o.__)("Easily create landing pages, tickets, and certificates in just a few steps.","eventin")),(0,a.createElement)(r.A,{className:"intro",gutter:[30,30],align:"middle"},m.W.map(e=>(0,a.createElement)(l.A,{xs:24,sm:24,md:12,lg:8,key:e.key},(0,a.createElement)(u.$4,{color:e.color},(0,a.createElement)("div",{className:"card-image",style:{backgroundColor:e.bgColor}},(0,a.createElement)("img",{src:e.image})),(0,a.createElement)("div",{className:"content-wrapper"},(0,a.createElement)("h4",{className:"content-wrapper-title"},e.title),(0,a.createElement)(i.A,{vertical:!0,gap:4},e.lists.map(e=>(0,a.createElement)("p",{className:"content-wrapper-description",key:e},e))),(0,a.createElement)(d.Ay,{variant:d.Qq,sx:{color:e.color,fontSize:"16px",fontWeight:600},icon:(0,a.createElement)(c.NewTabOpenIcon,{color:e.color}),iconPosition:"end",onClick:()=>window.open(e.docs_link,"_blank")},(0,o.__)("Learn more","eventin"))))))),(0,a.createElement)(u.QV,null,(0,a.createElement)(s.PrimaryButton,{htmlType:"button",onClick:e},(0,a.createElement)(c.PlusOutlined,null),(0,o.__)("New Template","eventin")),v?(0,a.createElement)(p.sX,{onClick:t},null!==(f=g[v])&&void 0!==f?f:(0,a.createElement)(c.SelectEditorSettingsIcon,null),(0,a.createElement)("span",null,h)):(0,a.createElement)(p.sR,{onClick:t},(0,a.createElement)(c.SelectEditorSettingsIcon,null),(0,a.createElement)("span",null,(0,o.__)("Selected builder","eventin")))))}},5063(e,t,n){n.d(t,{$4:()=>r,GI:()=>i,QV:()=>o});var a=n(27154),l=n(69815);const i=l.default.div`
  background-color: white;
  max-width: 1200px;
  border-radius: 6px;
  margin: 20px auto;
  padding: 24px;
  .header-title {
    text-align: center;
    font-weight: 600;
    font-size: 30px;
    color: #000000;
    margin-top: 10px;
    margin-bottom: 0px;
  }
  .header-desc {
    color: #475569;
    font-size: 16px;
    text-align: center;
    margin-bottom: 30px;
  }

  .intro-title {
    font-weight: 600;
    font-size: 2rem;
    line-height: 38px;
    margin: 0 0 20px;
    color: #020617;
  }

  .intro-list {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    font-size: 1rem;
    gap: 8px;
    margin: 0 0 2rem;
    padding: 0;
    color: #020617;
    list-style: none;
    font-weight: 400;
  }
  .intro-button {
    display: flex;
    align-items: center;
    border-radius: 6px;
  }
`,r=(l.default.div`
  margin: 0;
  position: relative;

  @media screen and (max-width: 768px) {
    margin: 0 0 2rem;
  }

  img {
    display: block;
    max-width: 100%;
  }

  iframe {
    border: none;
    border-radius: 10px;
  }

  .video-play-button {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.2);
    color: #fff;
    width: 60px !important;
    height: 60px;
    border-color: #f0eafc;

    &:hover {
      background-color: ${a.PRIMARY_COLOR};
      color: #fff;
      border-color: transparent;
    }

    &:focus {
      outline: none;
    }
  }
`,l.default.button`
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  font-size: 16px;
  font-weight: 500;
  background: #f9f5ff;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  position: relative;
  transition: all 0.2s ease;

  &::before {
    content: "";
    position: absolute;
    inset: -2px;
    border-radius: 6px;
    padding: 1px;
    background: linear-gradient(to left top, #fc8229, #e93da0, #404ef0);
    -webkit-mask:
      linear-gradient(#fff 0 0) content-box,
      linear-gradient(#fff 0 0);
    mask:
      linear-gradient(#fff 0 0) content-box,
      linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
  }

  &:hover {
    transform: translateY(-1px);
    background: rgba(99, 102, 241, 0.04);
  }

  &:active {
    transform: translateY(0);
  }

  svg {
    color: #ff69b4;
  }
`,l.default.span`
  background: linear-gradient(90deg, #fc8327 0%, #e83aa5 50.5%, #3a4ff2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: rgba(0, 0, 0, 0);
  background-clip: text;
`,l.default.div`
  width: 100%;
  border: 1px solid #d9d9d9;
  border-radius: 6px;

  img {
    width: 100%;
  }

  .content-wrapper {
    padding: 20px;

    &-title {
      color: #0f172a;
      font-size: 20px;
      font-weight: 600;
      line-height: 28px;
      margin-top: 0px;
    }

    &-description {
      position: relative;
      margin: 0px 0px 12px 10px;
      color: #475569;
      font-size: 14px;
      &::before {
        content: "";
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: ${({color:e})=>e};
        border-radius: 100px;
        left: -20px;
        bottom: 5px;
      }
    }
  }
`),o=l.default.div`
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 16px;
  padding-top: 30px;
  padding-bottom: 10px;
`},57922(e,t,n){n.d(t,{A:()=>l});var a=n(86087);const l=e=>{const[t,n]=(0,a.useState)({event:null,ticket:null,certificate:null,speaker:null}),l=(0,a.useCallback)((e,t)=>{n(n=>({...n,[e]:{...t}}))},[]),i=(0,a.useCallback)(e=>t[e],[t]);return(0,a.useEffect)(()=>{Array.isArray(e)&&e.forEach(e=>{e.is_default&&n(t=>({...t,[e.type]:{...e}}))})},[e]),{selectedTemplates:t,selectTemplate:l,getSelectedTemplate:i}}},30828(e,t,n){n.r(t),n.d(t,{default:()=>w});var a=n(51609),l=n(29491),i=n(47143),r=n(86087),o=n(52619),c=n(27723),s=n(75093),d=n(87968),p=n(64282),m=n(42670),u=n(47079),g=n(48290),f=n(57922),v=n(6525),h=n(93051),x=n(12236),b=n(77247);const E=(0,i.withSelect)(e=>{const t=e("eventin/global");return{templateList:t.getTemplateList(),templateListLoading:t.getTemplateListLoading()}}),y=(0,i.withDispatch)(e=>({setShowCreateTemplateModal:e("eventin/global").setShowCreateTemplateModal})),w=(0,l.compose)([E,y])(e=>{const{setShowCreateTemplateModal:t,templateList:n,templateListLoading:l}=e,[i,E]=(0,r.useState)(window?.localized_data_obj?.selected_template_builder),[y,w]=(0,r.useState)(!1),[_,k]=(0,r.useState)("event"),{selectTemplate:A,getSelectedTemplate:T}=(0,f.A)(n),C=()=>{window?.localized_data_obj?.selected_template_builder||i?t(!0):w(!0)},S=async e=>{try{const t=await p.A.template.selectEventTemplate({id:e.id,type:e.type});t?.message&&A(e.type,{...e})}catch(e){(0,o.doAction)("eventin_notification",{type:"error",message:e.message})}},L=!(n&&n.length>0);return l?(0,a.createElement)("div",null,(0,a.createElement)(x.A,{title:(0,c.__)("Template Builder","eventin"),buttonText:(0,c.__)("New Template","eventin"),onClickCallback:C,selectedEditor:i,handleOpenEditorSelectModal:()=>w(!0),hideActions:!0}),(0,a.createElement)(d.A,null)):(0,a.createElement)(a.Fragment,null,(0,a.createElement)(x.A,{title:(0,c.__)("Template Builder","eventin"),buttonText:(0,c.__)("New Template","eventin"),onClickCallback:C,selectedEditor:i,handleOpenEditorSelectModal:()=>w(!0),hideActions:L}),n&&n.length>0?(0,a.createElement)(v.ff,{className:"eventin-page-wrapper"},(0,a.createElement)(b.A,{activeTab:_,setActiveTab:k,children:{event:(0,a.createElement)(h.Hf,{templates:n.filter(e=>"event"===e.type),onTemplateSelect:S,selectedTemplateId:T("event")?.id,isLoading:l}),tickets:(0,a.createElement)(h.bu,{templates:n.filter(e=>"ticket"===e.type),onTemplateSelect:S,selectedTemplateId:T("ticket")?.id,isLoading:l}),certificate:(0,a.createElement)(h.al,{templates:n.filter(e=>"certificate"===e.type),onTemplateSelect:S,selectedTemplateId:null,isLoading:l}),speaker:(0,a.createElement)(h.Ko,{templates:n.filter(e=>"speaker"===e.type),onTemplateSelect:S,selectedTemplateId:T("speaker")?.id,isLoading:l})}})):(0,a.createElement)(g.A,{onNewTemplate:C,onSelectedBuilder:()=>w(!0),selectedEditor:i}),(0,a.createElement)(m.A,{selectedEditor:i,setSelectedEditor:E,openSelectEditorModal:y,setOpenSelectEditorModal:w}),(0,a.createElement)(u.A,{selectedEditor:i,templateType:_}),(0,a.createElement)(s.FloatingHelpButton,null))})},6525(e,t,n){n.d(t,{ff:()=>l,gi:()=>o,sR:()=>i,sX:()=>r});var a=n(69815);const l=a.default.div`
	background-color: #f4f6fa;
	padding: 12px 32px;
	min-height: 100vh;
`,i=a.default.button`
	display: inline-flex;
	align-items: center;
	gap: 8px;
	padding: 6px 14px;
	border: 1px solid #dce3ec;
	border-radius: 8px;
	background: #fff;
	cursor: pointer;
	font-size: 14px;
	font-weight: 500;
	color: #374151;
	line-height: 1.5;
	transition: border-color 0.2s;

	&:hover {
		border-color: #aab4c0;
	}

	.anticon {
		display: flex;
		align-items: center;
	}

	.anticon svg {
		width: 18px;
		height: 18px;
	}
`,r=a.default.button`
	display: inline-flex;
	align-items: center;
	gap: 8px;
	padding: 6px 14px;
	border: 1px solid #dce3ec;
	border-radius: 8px;
	background: #fff;
	font-size: 14px;
	font-weight: 500;
	color: #374151;
	cursor: pointer;
	transition: border-color 0.2s;

	&:hover {
		border-color: #aab4c0;
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
`,o=a.default.div`
	padding: 20px;
	margin-top: -20px;
	.ant-tabs-nav-wrap {
		background-color: #fff;
	}
	.ant-tabs {
		.ant-tabs-tab {
			font-size: 16px;
			font-weight: 500;
			background: transparent;
			color: #262626;
			padding: 15px 20px;
		}
		.ant-tabs-content-holder {
			background-color: #ffffff;
			border: 1px solid #d9d9d9;
			border-radius: 8px;
			padding: 20px;
		}
		.ant-tabs-tab-active {
			background-color: #ffffff;
			border-bottom: 2px solid #d9d9d9;
		}
	}
`},84786(e,t,n){n.d(t,{A:()=>c});var a=n(51609),l=n(61751),i=n(90455),r=n(42058),o=n(36082);const c=({templates:e,onTemplateSelect:t,selectedTemplateId:n,isLoading:c=!1})=>{const{handleEdit:s,handleDeleteConfirm:d,handleDuplicate:p}=(0,r.A)();return c?(0,a.createElement)(o.g,null,Array.from({length:6}).map((e,t)=>(0,a.createElement)(o.O,{key:t}))):0===e.length?(0,a.createElement)(i.A,null):(0,a.createElement)(o.g,null,e.map(e=>(0,a.createElement)("div",{key:e.id,className:"template-card-item"},(0,a.createElement)(l.A,{selectedTemplateId:n,templateType:"certificate",handleClick:()=>t(e),handleDeleteConfirm:d,handleEdit:s,handleDuplicate:p,template:e}))))}},84979(e,t,n){n.d(t,{A:()=>s});var a=n(51609),l=n(10076),i=n(27723),r=n(16133),o=n(42058),c=n(36082);const s=({templates:e,onTemplateSelect:t,selectedTemplateId:n,isLoading:s=!1})=>{const{handleDeleteConfirm:d}=(0,o.A)();return s?(0,a.createElement)(c.g,null,Array.from({length:6}).map((e,t)=>(0,a.createElement)(c.O,{key:t}))):0===e.length?(0,a.createElement)(r.A,{description:(0,i.__)("No event templates found","eventin"),style:{marginTop:"40px"}}):(0,a.createElement)(c.g,null,e.map(e=>(0,a.createElement)("div",{key:e.id,className:"template-card-item"},(0,a.createElement)(l.A,{template:e,selectedTemplateId:n,onTemplateSelect:()=>t(e),handleDeleteConfirm:d}))))}},84455(e,t,n){n.d(t,{A:()=>s});var a=n(51609),l=n(61751),i=n(27723),r=n(16133),o=n(42058),c=n(36082);const s=({templates:e,onTemplateSelect:t,selectedTemplateId:n,isLoading:s=!1})=>{const{handleEdit:d,handleDeleteConfirm:p}=(0,o.A)();return s?(0,a.createElement)(c.g,null,Array.from({length:6}).map((e,t)=>(0,a.createElement)(c.O,{key:t}))):0===e.length?(0,a.createElement)(r.A,{description:(0,i.__)("No speaker templates found","eventin"),style:{marginTop:"40px"}}):(0,a.createElement)(c.g,null,e.map(e=>(0,a.createElement)("div",{key:e.id,className:"template-card-item"},(0,a.createElement)(l.A,{selectedTemplateId:n,templateType:"speaker",handleClick:()=>t(e),handleDeleteConfirm:p,handleEdit:d,template:e}))))}},40687(e,t,n){n.d(t,{A:()=>c});var a=n(51609),l=n(61751),i=n(49636),r=n(42058),o=n(36082);const c=({templates:e,onTemplateSelect:t,selectedTemplateId:n,isLoading:c=!1})=>{const{handleEdit:s,handleDeleteConfirm:d}=(0,r.A)();return c?(0,a.createElement)(o.g,null,Array.from({length:6}).map((e,t)=>(0,a.createElement)(o.O,{key:t}))):0===e.length?(0,a.createElement)(i.A,null):(0,a.createElement)(o.g,null,e.map(e=>(0,a.createElement)("div",{key:e.id,className:"template-card-item"},(0,a.createElement)(l.A,{selectedTemplateId:n,templateType:"ticket",handleClick:()=>t(e),handleDeleteConfirm:d,handleEdit:s,template:e}))))}},42058(e,t,n){n.d(t,{A:()=>c});var a=n(47143),l=n(52619),i=n(27723),r=n(80734),o=n(64282);const c=()=>{const{setTemplateList:e}=(0,a.useDispatch)("eventin/global"),t=(0,a.useSelect)(e=>e("eventin/global").getTemplateList());return{handleEdit:e=>{window.open(e,"_blank")},handleDeleteConfirm:(n,a)=>{(0,r.A)({title:(0,i.__)("Are you sure?","eventin"),content:(0,i.__)("Are you sure you want to delete this template?","eventin"),onOk:()=>a?(async n=>{const a=t||[];e(a.filter(e=>e.id!==n));try{await o.A.template.deleteStaticTemplate(n),(0,l.doAction)("eventin_notification",{type:"success",message:(0,i.__)("Template removed from list successfully!","eventin")})}catch(t){e(a),(0,l.doAction)("eventin_notification",{type:"error",message:(0,i.__)("Failed to remove template from list!","eventin")})}})(n):(async n=>{const a=t||[];e(a.filter(e=>e.id!==n));try{await o.A.template.deleteTemplate(n),(0,l.doAction)("eventin_notification",{type:"success",message:(0,i.__)("Successfully deleted the template!","eventin")})}catch(t){e(a),(0,l.doAction)("eventin_notification",{type:"error",message:(0,i.__)("Failed to delete the template!","eventin")})}})(n)})},handleDuplicate:async n=>{const a={...n,id:"__optimistic__",name:`${n.name} - ${(0,i.__)("Copy","eventin")}`};e([...t||[],a]);try{const a=await o.A.template.cloneTemplate(n.id),r=await o.A.template.singleTemplate(a.id);await o.A.template.updateTemplate(a.id,{...r,name:`${n.name} - ${(0,i.__)("Copy","eventin")}`}),e([...t||[],{...r,name:`${n.name} - ${(0,i.__)("Copy","eventin")}`}]),(0,l.doAction)("eventin_notification",{type:"success",message:(0,i.__)("Template duplicated successfully!","eventin")})}catch(n){e(t||[]),(0,l.doAction)("eventin_notification",{type:"error",message:(0,i.__)("Failed to duplicate the template!","eventin")})}}}}},93051(e,t,n){n.d(t,{Hf:()=>a.A,Ko:()=>r.A,al:()=>i.A,bu:()=>l.A});var a=n(84979),l=n(40687),i=n(84786),r=n(84455)},12236(e,t,n){n.d(t,{A:()=>g});var a=n(51609),l=n(56427),i=n(27723),r=n(92911),o=n(32099),c=n(54725),s=n(75093),d=n(27154),p=n(6525);const m={elementor:(0,a.createElement)(c.ElementorTemplateIcon,null),gutenberg:(0,a.createElement)(c.WordpressIcon,null)};function u({builderKey:e}){var t;return null!==(t=m[e])&&void 0!==t?t:(0,a.createElement)(c.SelectEditorSettingsIcon,null)}function g(e){const{title:t,buttonText:n,onClickCallback:m,handleOpenEditorSelectModal:g,selectedEditor:f,hideActions:v=!1}=e,h=f||window.localized_data_obj?.selected_template_builder,x=h?h.charAt(0).toUpperCase()+h.slice(1):"";return(0,a.createElement)(l.Fill,{name:d.PRIMARY_HEADER_NAME},(0,a.createElement)(r.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,a.createElement)(s.LogoWithTitle,{title:t}),!v&&(0,a.createElement)(r.A,{align:"center",gap:8,wrap:"wrap"},h?(0,a.createElement)(o.A,{title:(0,i.__)("Change builder","eventin"),placement:"bottomRight"},(0,a.createElement)(p.sX,{onClick:g},(0,a.createElement)(u,{builderKey:h}),(0,a.createElement)("span",null,x))):(0,a.createElement)(o.A,{title:(0,i.__)("Open builder select","eventin"),placement:"bottomRight"},(0,a.createElement)(p.sR,{onClick:g},(0,a.createElement)(c.SelectEditorSettingsIcon,null),(0,a.createElement)("span",null,(0,i.__)("Selected builder","eventin")))),(0,a.createElement)(s.PrimaryButton,{htmlType:"button",onClick:m},(0,a.createElement)(c.PlusOutlined,null),n))))}},77247(e,t,n){n.d(t,{A:()=>c});var a=n(51609),l=n(27723),i=n(80560),r=n(54725),o=n(6525);const c=({activeTab:e,setActiveTab:t,children:n})=>{const c=[{key:"event",label:(0,a.createElement)("span",{style:{display:"flex",alignItems:"center",gap:"8px"}},(0,a.createElement)(r.LandingPageIcon,null),(0,l.__)("Landing page","eventin")),children:n.event},{key:"ticket",label:(0,a.createElement)("span",{style:{display:"flex",alignItems:"center",gap:"8px"}},(0,a.createElement)(r.TicketIcon,null),(0,l.__)("Tickets","eventin")),children:n.tickets},{key:"certificate",label:(0,a.createElement)("span",{style:{display:"flex",alignItems:"center",gap:"8px"}},(0,a.createElement)(r.CertificateIcon,null),(0,l.__)("Certificate","eventin")),children:n.certificate},{key:"speaker",label:(0,a.createElement)("span",{style:{display:"flex",alignItems:"center",gap:"8px"}},(0,a.createElement)(r.SpeakerAndOrganizerIcon,null),(0,l.__)("Speaker","eventin")),children:n.speaker}];return(0,a.createElement)(o.gi,null,(0,a.createElement)(i.A,{activeKey:e,onChange:t,items:c,style:{marginTop:"24px"}}))}},72725(e,t,n){n.d(t,{P:()=>a});const a=(e=[],t)=>{if(!Array.isArray(e)||0===e.length)return!1;const n=e.find(e=>e&&e.id===t);return!!n&&Boolean(n.is_active)}}}]);