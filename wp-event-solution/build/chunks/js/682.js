"use strict";(self.webpackChunkwp_event_solution=self.webpackChunkwp_event_solution||[]).push([[682,585],{34932:(e,t,n)=>{n.d(t,{A:()=>i});var a=n(51609),l=n(29392);function i(e){const{sx:t,...n}=e;return(0,a.createElement)(l.A,{className:"eventin-color-picker",size:"large",showText:e=>(0,a.createElement)("span",null," ",e.toHexString()," "),style:t,...n})}},19121:(e,t,n)=>{n.d(t,{A:()=>u});var a=n(51609),l=n(27723),i=n(69815),r=n(47767),o=n(67313),s=n(92911),c=n(7638),m=n(54725);const{Title:d,Text:p}=o.A,g=i.default.div`
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	height: 100%;
	text-align: center;
	font-family: Arial, sans-serif;
	color: #6b7280; /* Neutral gray */
	padding: 30px 0px;
`,u=()=>{const e=(0,r.useNavigate)();return(0,a.createElement)(g,null,(0,a.createElement)("div",null,(0,a.createElement)(m.EmptyTemplateIcon,null)),(0,a.createElement)(d,{level:4},(0,l.__)("No data is available.","eventin")),(0,a.createElement)(p,null,(0,l.__)(" This section will be updated once data has been collected.","eventin")),(0,a.createElement)(s.A,{gap:10},(0,a.createElement)(c.Ay,{onClick:()=>window.open("https://support.themewinter.com/docs/plugins/plugin-docs/templates/how-to-change-event-templates/","_blank"),variant:c.Vt,sx:{marginTop:"20px"}},(0,l.__)("Learn more","eventin")),(0,a.createElement)(c.Ay,{onClick:()=>e("/template-builder"),variant:c.zB,sx:{marginTop:"20px"}},(0,l.__)("Create a new template","eventin"))))}},32122:(e,t,n)=>{n.d(t,{A:()=>h});var a=n(51609),l=n(86087),i=n(27723),r=n(67313),o=n(77278),s=n(92911),c=n(69815),m=n(75093),d=n(27154),p=n(48842),g=n(7638),u=n(45115),v=n(54725);const{Text:_}=r.A,E=(0,c.default)(o.A)`
	position: relative;
	min-width: 200px;
	min-height: 220px;
	padding: 0;
	border: ${e=>e.isSelected?`1px solid ${d.PRIMARY_COLOR}`:"1px solid #f0f0f0"};
	border-radius: 6px;
	background-image: url( ${e=>e.bg} );
	background-size: cover;
	background-position: center center;
	background-repeat: no-repeat;

	.ant-card-body {
		padding: 0;
		height: 100%;
	}

	.template-name-overlay {
		position: absolute;
		bottom: 0;
		left: 0;
		width: 100%;
		height: 50px;
		background: #f0f0f0;
		border-radius: 0 0 5px 5px;
		transition: all 0.2s;

		.title {
			display: flex;
			align-items: center;
			justify-content: center;
			height: 100%;
		}

		.buttons {
			display: none;
			opacity: 0;
			height: 100%;
			transition: opacity 0.2s;
		}
	}

	&:hover {
		.template-name-overlay {
			.title {
				display: none;
			}
			.buttons {
				display: flex;
				opacity: 1;
			}
		}
	}
`,h=({src:e,alt:t,id:n,title:r,pro:o,selected:c,handleClick:d,externalLink:h,...f})=>{const[x,A]=(0,l.useState)(!1),y=!!window.localized_data_obj.evnetin_pro_active;return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(E,{className:"etn-add-ticket",bg:e,templateId:n,isSelected:c},(0,a.createElement)(s.A,{className:"template-name-overlay",align:"center",justify:"center"},(0,a.createElement)("div",{className:"title"},(0,a.createElement)(_,{ellipsis:{tooltip:r},style:{margin:0,color:"#333",fontWeight:600,width:"220px"}},r)),o&&!y?(0,a.createElement)(s.A,{className:"buttons",align:"center",justify:"center"},(0,a.createElement)(m.ProButton,null)):(0,a.createElement)(s.A,{className:"buttons",align:"center",justify:"space-around",style:{width:"100%"}},h?(0,a.createElement)("a",{href:h,target:"_blank",rel:"noreferrer"},(0,a.createElement)(g.Ay,{variant:m.primary,sx:{height:"32px"}},(0,i.__)("Preview","eventin"))):(0,a.createElement)(g.Ay,{onClick:()=>A(!0),variant:m.primary,sx:{height:"32px"}},(0,i.__)("Preview","eventin")),c?(0,a.createElement)(p.A,{sx:{display:"flex",alignItems:"center",gap:"5px"}},(0,a.createElement)(v.CheckMarkFill,null)," ",(0,i.__)("Selected","eventin")):(0,a.createElement)(g.Ay,{onClick:d,variant:m.outlined,sx:{backgroundColor:"#fff",height:"32px","&:hover":{backgroundColor:"#fff"}}},(0,i.__)("Select","eventin"))))),(0,a.createElement)(u.A,{showModal:x,setShowModal:A,templateId:n}))}},41407:(e,t,n)=>{n.d(t,{A:()=>c});var a=n(51609),l=n(27723),i=n(69815),r=n(7638),o=n(54725);const s=i.default.div`
	position: relative;
	border: 1px solid #f0f0f0;
	border-radius: 6px;
	width: 100%;
	min-height: 220px;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	background-color: #ffffff;
	transition: all 0.3s;

	.none-placeholder {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		color: #334155;
		p {
			font-size: 18px;
			margin: 0px;
		}
	}

	.etn-none-select-button-area {
		display: none;
		opacity: 0;
		position: absolute;
		bottom: 5px;
		width: 90%;
		border-radius: 5px;
		transition: opacity 0.3s;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: #f5f5f5;
		padding: 8px;
	}

	&:hover {
		.etn-none-select-button-area {
			display: flex;
			opacity: 1;
		}
	}
`,c=({onClick:e})=>(0,a.createElement)(s,{className:"etn-template-none-card"},(0,a.createElement)("div",{className:"none-placeholder"},(0,a.createElement)(o.DisabledIcon,null),(0,a.createElement)("p",null,(0,l.__)("None","eventin"))),(0,a.createElement)("div",{className:"etn-none-select-button-area"},(0,a.createElement)(r.Ay,{onClick:e,variant:r.zB,sx:{height:"30px"}},(0,l.__)("Select","eventin"))))},7746:(e,t,n)=>{n.d(t,{A:()=>o});var a=n(51609),l=n(7313),i=n(48842),r=n(57237);const o=e=>{const{titleText:t,labelText:n,titleIcon:o,infoTooltipText:s}=e||{};return(0,a.createElement)("div",null,t&&(0,a.createElement)(r.A,{level:4,className:"etn-section-subtitle"},o&&o," ",t),(0,a.createElement)("div",{style:{marginTop:"20px"}},(0,a.createElement)(i.A,{style:{fontWeight:600,fontSize:"16px",lineHeight:"24px",color:"#334155"}},n,(0,a.createElement)(l.A,{title:s}))))}},50331:(e,t,n)=>{n.d(t,{A:()=>d});var a=n(51609),l=n(27723),i=n(86087),r=n(69815),o=n(7638),s=n(45115);const c=r.default.div`
	position: relative;
	border: 1px solid #f0f0f0;
	border-radius: 6px;
	min-width: 200px;
	min-height: 220px;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	background-color: #ffffff;
	transition: all 0.3s;
	background-image: url( ${e=>e.bg} );
	background-size: ${e=>e.templateId?"cover":"150px 160px"};
	background-position: center center;
	background-repeat: no-repeat;

	.etn-template-select-button {
		display: none;
		opacity: 0;
		flex-direction: column;
		gap: 10px;
		align-items: center;
		justify-content: center;
		width: 100%;
		height: 100%;
	}

	.template-name-overlay {
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		padding: 8px;
		background: #f0f0f0;
		color: white;
		opacity: 0;
		transition: opacity 0.2s;
	}

	&:hover {
		.etn-template-select-button {
			opacity: 1;
			display: flex;
		}
		.template-name-overlay {
			opacity: 1;
		}
	}
`,m=!!window.localized_data_obj.evnetin_pro_active,d=({buttonText:e=(0,l.__)("Select Template","eventin"),src:t,handleClick:n=()=>{},isPro:r=!1,selectedTemplate:d=null,externalLink:p=""})=>{const[g,u]=(0,i.useState)(!1),v=d?.id;return(0,a.createElement)(c,{className:"etn-add-ticket",bg:t,templateId:v},(0,a.createElement)("div",{className:"template-name-overlay"},r&&!m?(0,a.createElement)("div",{className:"etn-template-select-button"},(0,a.createElement)(o.Oc,null)):(0,a.createElement)("div",{className:"etn-template-select-button"},(0,a.createElement)(o.Ay,{onClick:n,variant:o.Vt,sx:{borderRadius:"8px",paddingTop:"6px",backgroundColor:"#fff",width:"160px","&:hover":{backgroundColor:"#fff"}}},e),d&&(p?(0,a.createElement)("a",{href:p,target:"_blank",rel:"noreferrer"},(0,a.createElement)(o.Ay,{variant:o.zB,sx:{marginTop:"10px",borderRadius:"8px",width:"160px"}},(0,l.__)("Preview","eventin"))):(0,a.createElement)(o.Ay,{onClick:()=>u(!0),variant:o.zB,sx:{marginTop:"10px",borderRadius:"8px",width:"160px"}},(0,l.__)("Preview","eventin"))))),(0,a.createElement)(s.A,{showModal:g,setShowModal:u,templateId:v}))}},92051:(e,t,n)=>{n.d(t,{A:()=>p});var a=n(51609),l=n(27723),i=n(500),r=n(47767),o=n(7638),s=n(19121),c=n(69815);const m=c.default.div`
	display: flex;
	align-items: center;
	flex-wrap: wrap;
	gap: 20px;
`,d=c.default.h3`
	font-size: 26px;
	font-weight: 600;
	line-height: 1;
	margin: 0;
`,p=({modalOpen:e,setModalOpen:t,modalTitle:n,children:c,isTemplateAvailable:p=!0})=>{const g=(0,r.useNavigate)(),u=(0,a.createElement)(m,null,(0,a.createElement)(d,null,n),(0,a.createElement)(o.Ay,{onClick:()=>g("/template-builder"),variant:o.Vt},(0,l.__)("Create Template","eventin")));return(0,a.createElement)(i.A,{title:p?u:n,open:e,onCancel:()=>t(!1),onOk:()=>t(!1),onOkText:(0,l.__)("Save","eventin"),footer:p?void 0:null,destroyOnClose:!0,width:800,centered:!0,styles:{body:{maxHeight:"calc(80vh - 110px)",overflowY:"auto",padding:"24px"},content:{padding:0}}},p?(0,a.createElement)("div",{className:"etn-template-selection-modal-container"},(0,a.createElement)("div",{className:"etn-template-list-area"},c)):(0,a.createElement)(s.A,null))}},14928:(e,t,n)=>{n.d(t,{A:()=>l});var a=n(86087);const l=(e,t,n,l,i)=>{const[r,o]=(0,a.useState)(null);return(0,a.useEffect)((()=>{if("string"==typeof n||"number"==typeof n){const a=[...e,...t].find((e=>String(e?.id)===String(n)));o(a||null)}}),[n,e,t]),{selectedTemplate:r,handleSelectionChange:(e,t)=>{const{id:n,is_pro:a}=e||{};(!a||a&&t)&&(o(e),l.setFieldsValue({[i]:String(n)}))},clearSelection:()=>{o(null),l.setFieldsValue({[i]:""})}}}},57770:(e,t,n)=>{n.d(t,{A:()=>l});var a=n(18537);function l(e,t){if(Array.isArray(e))return JSON.parse(JSON.stringify(e)).map((e=>(e[t]=(0,a.decodeEntities)(e[t]),e)))}},59985:(e,t,n)=>{n.d(t,{A:()=>u});var a=n(51609),l=n(19549),i=n(29491),r=n(47143),o=n(52619),s=n(27723),c=n(54725),m=n(7638),d=n(64282);const{confirm:p}=l.A,g=(0,r.withDispatch)((e=>{const t=e("eventin/global");return{refreshEventCategories:()=>t.invalidateResolution("getEventCategories")}})),u=(0,i.compose)(g)((function(e){const{refreshEventCategories:t,record:n}=e;return(0,a.createElement)(m.Ay,{variant:m.Vt,onClick:()=>{p({title:(0,s.__)("Are you sure?","eventin"),icon:(0,a.createElement)(c.DeleteOutlinedEmpty,null),content:(0,s.__)("Are you sure you want to delete this event?","eventin"),okText:(0,s.__)("Delete","eventin"),okButtonProps:{type:"primary",danger:!0,classNames:"delete-btn"},centered:!0,onOk:async()=>{try{await d.A.eventCategories.deleteCategory(n.id),t(),(0,o.doAction)("eventin_notification",{type:"success",message:(0,s.__)("Successfully deleted the category!","eventin")})}catch(e){console.error("Error deleting category",e),(0,o.doAction)("eventin_notification",{type:"error",message:(0,s.__)("Failed to delete the category!","eventin")})}},onCancel(){}})}},(0,a.createElement)(c.DeleteOutlined,{width:"16",height:"16"}))}))},70696:(e,t,n)=>{n.d(t,{A:()=>s});var a=n(51609),l=n(86087),i=n(54725),r=n(7638),o=n(54702);function s(e){const{record:t}=e,{setCategoriesData:n}=(0,l.useContext)(o.EventCategoryContext);return(0,a.createElement)(r.Ay,{variant:r.Vt,onClick:()=>{n((e=>({...e,editData:t,isModalOpen:!0})))}},(0,a.createElement)(i.EditOutlined,{width:"16",height:"16"}))}},77914:(e,t,n)=>{n.d(t,{A:()=>o});var a=n(51609),l=n(90070),i=n(59985),r=n(70696);function o(e){const{record:t}=e;return(0,a.createElement)(l.A,{size:"small",className:"event-actions"},(0,a.createElement)(r.A,{record:t}),(0,a.createElement)(i.A,{record:t}))}},69607:(e,t,n)=>{n.d(t,{A:()=>r});var a=n(51609),l=n(27723),i=n(77914);const r=[{title:(0,l.__)("Category","eventin"),dataIndex:"name",key:"name",width:330,render:e=>(0,a.createElement)("p",{className:"event-title"},e)},{title:(0,l.__)("Parent Category","eventin"),key:"parent",dataIndex:"parent_name",render:e=>(0,a.createElement)("span",{className:"author"},e||"-")},{title:(0,l.__)("Description","eventin"),dataIndex:"description",key:"description",render:e=>(0,a.createElement)("span",{className:"author"},e)},{title:(0,l.__)("Action","eventin"),key:"action",width:120,render:(e,t)=>(0,a.createElement)(i.A,{record:t})}]},6371:(e,t,n)=>{n.d(t,{A:()=>h});var a=n(51609),l=n(92911),i=n(36492),r=n(79888),o=n(29491),s=n(47143),c=n(86087),m=n(27723),d=n(54725),p=n(79351),g=n(62215),u=n(61149),v=n(64282),_=n(54702);const E=(0,s.withSelect)((e=>({categories:e("eventin/global").getEventCategories()}))),h=(0,o.compose)(E)((e=>{const{selectedCategories:t,setSelectedCategories:n,categories:o}=e,{setCategoriesData:s}=(0,c.useContext)(_.EventCategoryContext),E=!!t?.length;return(0,a.createElement)(u.O,{className:"filter-wrapper"},(0,a.createElement)(l.A,{justify:"space-between",wrap:"wrap",align:"center",gap:8},(0,a.createElement)(l.A,{justify:"start",align:"center",gap:8},E?(0,a.createElement)(p.A,{refreshListName:"getEventCategories",selectedCount:t?.length,callbackFunction:async()=>{const e=(0,g.A)(t);await v.A.eventCategories.deleteCategory(e),n([])},setSelectedRows:n}):(0,a.createElement)(i.A,{placeholder:(0,m.__)("Parent Category","eventin"),options:o||[],size:"default",style:{minWidth:"200px",width:"100%"},fieldNames:{label:"name",value:"id"},onChange:e=>{s((t=>({...t,filter:{...t.filter,parentCategory:e}})))},allowClear:!0})),!E&&(0,a.createElement)(r.A,{style:{minWidth:"100px"},className:"event-filter-by-name",placeholder:(0,m.__)("Search by category name","eventin"),size:"default",prefix:(0,a.createElement)(d.SearchIconOutlined,null),onChange:e=>{s((t=>({...t,filter:{...t.filter,searchTerm:e.target.value}})))},allowClear:!0})))}))},33394:(e,t,n)=>{n.d(t,{A:()=>f});var a=n(51609),l=n(86087),i=n(75063),r=n(16784),o=n(74353),s=n.n(o),c=n(98867),m=n.n(c),d=n(8906),p=n.n(d),g=n(27723),u=n(54702),v=n(6371),_=n(69607),E=n(61149),h=n(75093);function f(e){const{categoryList:t,isLoading:n}=e,[o,s]=(0,l.useState)([]),[c,m]=(0,l.useState)([]),{categoriesData:d}=(0,l.useContext)(u.EventCategoryContext),p={selectedRowKeys:c,onChange:e=>{m(e)}};return(0,l.useEffect)((()=>{(()=>{let e=t;d?.filter?.parentCategory&&(e=e?.filter((e=>e?.parent===d?.filter?.parentCategory))),d?.filter?.searchTerm&&(e=e?.filter((e=>e?.name?.toLowerCase()?.includes(d?.filter?.searchTerm?.toLowerCase())))),s(e)})()}),[t,d]),n?(0,a.createElement)(E.f,{className:"eventin-page-wrapper"},(0,a.createElement)(i.A,{active:!0})):(0,a.createElement)(E.f,{className:"eventin-page-wrapper"},(0,a.createElement)("div",{className:"event-list-wrapper"},(0,a.createElement)(v.A,{selectedCategories:c,setSelectedCategories:m,categories:t}),(0,a.createElement)(r.A,{className:"eventin-data-table",columns:_.A,dataSource:o,rowSelection:p,rowKey:e=>e.id,scroll:{x:800},sticky:{offsetHeader:100},pagination:{showTotal:(e,t)=>(0,a.createElement)(h.CustomShowTotal,{totalCount:e,range:t,listText:(0,g.__)("categories","eventin")})}})))}s().extend(m()),s().extend(p())},61149:(e,t,n)=>{n.d(t,{O:()=>i,f:()=>l});var a=n(69815);const l=a.default.div`
	background-color: #f4f6fa;
	padding: 12px 32px;
	min-height: 100vh;

	.ant-table-wrapper {
		padding: 20px;
		background-color: #fff;
	}

	.event-categories-wrapper {
		box-shadow: 0 2px 8px 0 rgba( 0, 0, 0, 0.15 );
		border-radius: 0 0 4px 4px;
	}

	.ant-table-wrapper {
		border-radius: 0 0 4px 4px;
	}

	.ant-table-thead {
		> tr {
			> th {
				background-color: transparent;
				padding-top: 10px;
				font-weight: 500;
				color: #747474;
				&:before {
					display: none;
				}
			}
		}
	}

	.event-title {
		color: #181818;
		font-size: 1rem;
		font-weight: 400;
		line-height: 24px;
		display: inline-flex;
		margin-bottom: 6px;
	}

	.event-location,
	.event-date-time {
		color: #858585;
		margin: 0;
		line-height: 1.4;
		font-size: 14px;
	}
	.event-date-time {
		display: flex;
		align-items: center;
		gap: 4px;
	}

	.event-location {
		margin-bottom: 4px;
	}

	.event-actions {
		.ant-btn {
			padding: 0;
			width: 28px;
			height: 28px;
			line-height: 1;
			display: flex;
			justify-content: center;
			align-items: center;
			border-color: #c9c9c9;
			color: #c9c9c9;
			background-color: #fff;
		}
	}

	.ant-tag {
		border-radius: 20px;
		font-size: 12px;
		font-weight: 400;
		padding: 4px 13px;
		min-width: 80px;
		text-align: center;
	}

	.ant-tag.event-category {
		background-color: transparent;
		font-size: 1rem;
		color: #181818;
		padding: 0;
		text-align: left;
	}

	.author {
		color: #181818;
		font-size: 1rem;
		text-transform: capitalize;
	}
`,i=a.default.div`
	padding: 18px 24px;
	background: #fff;
	border-radius: 4px 4px 0 0;
	border-bottom: 1px solid #ddd;

	.ant-form-item {
		margin-bottom: 0;
	}
	.ant-select-single {
		height: 36px;
		width: 120px !important;
	}

	.ant-picker {
		height: 36px;
	}
	.event-filter-by-name {
		height: 36px;
		border: 1px solid #ddd;
		max-width: 220px;

		input.ant-input {
			min-height: 32px !important;
		}
	}
`},54702:(e,t,n)=>{n.r(t),n.d(t,{EventCategoryContext:()=>p,default:()=>u});var a=n(51609),l=n(29491),i=n(47143),r=n(86087),o=n(27723),s=n(57770),c=n(70034),m=n(61397),d=n(33394);const p=(0,r.createContext)(),g=(0,i.withSelect)((e=>{const t=e("eventin/global");return{categoryList:t.getEventCategories(),isLoading:t.isResolving("getEventCategories")}})),u=(0,l.compose)(g)((function(e){const{categoryList:t,isLoading:n}=e;let l=(0,s.A)(t,"name");l=(0,s.A)(l,"description");const[i,g]=(0,r.useState)({filter:{parentCategory:null,searchTerm:null},editData:{},isModalOpen:!1}),u=e=>{g((t=>({...t,isModalOpen:e})))};return(0,a.createElement)(p.Provider,{value:{categoriesData:i,setCategoriesData:g}},(0,a.createElement)("div",{className:"event-categories-wrapper eventin-event-categories-wrapper"},(0,a.createElement)(m.A,{title:(0,o.__)("Event Categories","eventin"),onClickCallback:()=>u(!0),buttonText:(0,o.__)("New Category","eventin")}),(0,a.createElement)(d.A,{isLoading:n,categoryList:l}),(0,a.createElement)(c.A,{modalOpen:i.isModalOpen,setModalOpen:u})))}))},70034:(e,t,n)=>{n.d(t,{A:()=>h});var a=n(51609),l=n(29491),i=n(47143),r=n(86087),o=n(52619),s=n(27723),c=n(60742),m=n(36492),d=n(500),p=n(10012),g=n(54702),u=n(64282);const v=(0,o.applyFilters)("eventin-category-meta",(()=>null)),_=(0,i.withDispatch)((e=>{const t=e("eventin/global");return{refreshCategoryList:()=>t.invalidateResolution("getEventCategories")}})),E=(0,i.withSelect)((e=>{const t=e("eventin/global");return{categories:t.getEventCategories(),isLoading:t.isResolving("getEventCategories")}})),h=(0,l.compose)([E,_])((e=>{const{modalOpen:t,setModalOpen:n,refreshCategoryList:l,categories:i}=e,[o,_]=(0,r.useState)(!1),[E]=c.A.useForm(),{categoriesData:h,setCategoriesData:f}=(0,r.useContext)(g.EventCategoryContext)||{},x=h?.editData?.id;return(0,r.useEffect)((()=>{if(t){if(x){const e=h?.editData,t=e?.parent;E.setFieldsValue({...e,parent:0===parseInt(t)?null:t})}}else E.resetFields(),f&&f((e=>({...e,editData:null})))}),[t]),(0,a.createElement)(d.A,{title:(0,s.__)(x?"Edit Category":"New Category","eventin"),open:t,onCancel:()=>{n(!1)},cancelText:(0,s.__)("Cancel","eventin"),okText:x?(0,s.__)("Update Category","eventin"):(0,s.__)("Add Category","eventin"),onOk:async()=>{_(!0);try{await E.validateFields();const e=E.getFieldsValue(!0);if(x){const t=h?.editData?.id;await u.A.eventCategories.updateCategory(t,e)}else await u.A.eventCategories.createCategory(e);E.resetFields(),l(),n(!1)}catch(e){console.error("Couldn't Create Category"),console.error(e.message)}finally{_(!1)}},confirmLoading:o,destroyOnClose:!0},(0,a.createElement)(c.A,{layout:"vertical",form:E},(0,a.createElement)("div",null,(0,a.createElement)(p.ks,{placeholder:"Category",name:"name",label:(0,s.__)("Category Name","eventin"),size:"middle",rules:[{required:!0,message:(0,s.__)("Category Name is Required!","eventin")}],required:!0}),(0,a.createElement)(c.A.Item,{name:"parent",label:(0,s.__)("Parent Category","eventin")},(0,a.createElement)(m.A,{options:i||[],size:"large",placeholder:(0,s.__)("Select Parent Category (if any)","eventin"),fieldNames:{value:"id",label:"name"}})),(0,a.createElement)(v,{form:E}),(0,a.createElement)(p.No,{className:"event-category-description",label:(0,s.__)("Description","eventin"),name:"description",placeholder:(0,s.__)("Category description","eventin")}))))}))},3087:(e,t,n)=>{n.d(t,{A:()=>x});var a=n(51609),l=n(29491),i=n(47143),r=n(86087),o=n(27723),s=n(52619),c=n(60742),m=n(47152),d=n(16370),p=n(500),g=n(13444),u=n(10012),v=n(91807),_=n(64282),E=n(70164);const h=(0,i.withSelect)((e=>{const t=e("eventin/global");return{speakerCategories:t.getSpeakerCategories(),isLoading:t.isResolving("getSpeakerCategories")}})),f=(0,i.withDispatch)((e=>{const t=e("eventin/global");return{refreshSpeakerList:()=>t.invalidateResolution("getSpeakerList")}})),x=(0,l.compose)(h,f)((e=>{const{modalOpen:t,setModalOpen:n,speakerCategories:l,refreshSpeakerList:i,formType:h}=e,[f]=c.A.useForm(),[x,A]=(0,r.useState)(!1),y="organizer"===h;return(0,a.createElement)(p.A,{title:(0,o.__)(`Add New ${y?"Organizer":"Speaker"} `,"eventin"),open:t,onCancel:()=>n(!1),cancelText:(0,o.__)("Cancel","eventin"),okText:(0,o.__)(`Add ${y?"Organizer":"Speaker"} `,"eventin"),onOk:async()=>{A(!0);try{await f.validateFields();const e={...f.getFieldsValue(!0),category:y?["organizer"]:["speaker"]},t=await _.A.speakers.createSpeaker(e);t?.id&&(i(),n(!1),(0,s.doAction)("eventin_notification",{type:"success",message:(0,o.__)("Successfully Created "+(y?"Organizer":"Speaker"),"eventin")}))}catch(e){console.log("error message:",e),(0,s.doAction)("eventin_notification",{type:"error",message:e?.message})}finally{A(!1)}},confirmLoading:x,destroyOnClose:!0,styles:{body:{height:"70vh",overflowY:"auto",overflowX:"hidden",scrollbarWidth:"thin"}}},(0,a.createElement)(c.A,{layout:"vertical",form:f,scrollToFirstError:!0,preserve:!1},(0,a.createElement)(m.A,{gutter:[10,0],style:{paddingRight:"4px"}},(0,a.createElement)(d.A,{xs:24},(0,a.createElement)(c.A.Item,{name:"name",label:(0,o.__)((y?"Organizer":"Speaker")+" name","eventin"),rules:[{required:!0,message:(y?"Organizer":"Speaker")+" name is required!"}]},(0,a.createElement)(u.ks,{placeholder:(0,o.__)("Name of "+(y?"organizer":"speaker"),"eventin"),size:"middle"}))),(0,a.createElement)(d.A,{md:12,xs:24},(0,a.createElement)(c.A.Item,{name:"designation",label:"Job Title"},(0,a.createElement)(u.ks,{placeholder:(0,o.__)("Designation of "+(y?"organizer":"speaker"),"eventin")}))),(0,a.createElement)(d.A,{md:12,xs:24},(0,a.createElement)(c.A.Item,{name:"speaker_group",label:(0,o.__)((y?"Organizer":"Speaker")+" Group","eventin"),rules:[{required:!0,message:"Speaker group is required!"}]},(0,a.createElement)(g.A,{options:l,placeholder:(0,o.__)(`Select ${y?"organizer":"speaker"} group`,"eventin"),fieldNames:{label:"name",value:"id"}},(0,a.createElement)(E.A,null)))),(0,a.createElement)(d.A,{md:12,xs:24},(0,a.createElement)(c.A.Item,{name:"email",label:(0,o.__)("Email address","eventin"),rules:[{required:!0,type:"email",message:(0,o.__)("Please enter valid email address!","eventin")}]},(0,a.createElement)(u.ks,{placeholder:(0,o.__)("Email address of "+(y?"organizer":"speaker"),"eventin")}))),(0,a.createElement)(d.A,{md:12,xs:24},(0,a.createElement)(c.A.Item,{name:"company_name",label:(0,o.__)("Company name","eventin")},(0,a.createElement)(u.ks,{placeholder:(0,o.__)("Company name","eventin")}))),(0,a.createElement)(d.A,{xs:24},(0,a.createElement)("p",{style:{marginBottom:"8px",fontSize:"16px",fontWeight:600,color:"#334155"}},(0,o.__)((y?"Organizer":"Speaker")+" photo","eventin")),(0,a.createElement)(v.nz,{form:f,name:"image"})))))}))},70164:(e,t,n)=>{n.d(t,{A:()=>g});var a=n(51609),l=n(86087),i=n(27723),r=n(29491),o=n(47143),s=n(92911),c=n(7638),m=n(10012),d=n(64282);const p=(0,o.withDispatch)((e=>{const t=e("eventin/global");return{refreshCategoryList:()=>t.invalidateResolution("getSpeakerCategories")}})),g=(0,r.compose)(p)((e=>{const{refreshCategoryList:t}=e,[n,r]=(0,l.useState)(null),[o,p]=(0,l.useState)(!1),g=async()=>{p(!0);try{await d.A.speakerCategories.createCategory({name:n}),r(null),t()}catch(e){console.error("Couldn't create speaker category!"),console.error(e.message)}finally{p(!1)}};return(0,a.createElement)(s.A,{gap:5},(0,a.createElement)(m.ks,{placeholder:(0,i.__)("New Group Name","eventin"),value:n,onChange:e=>{r(e.target.value)},onPressEnter:g}),(0,a.createElement)(c.Ay,{variant:c.zB,onClick:g,loading:o},(0,i.__)("Add New","eventin")))}))},31954:(e,t,n)=>{n.d(t,{A:()=>_});var a=n(51609),l=n(29491),i=n(47143),r=n(86087),o=n(27723),s=n(52619),c=n(60742),m=n(10012),d=n(500),p=n(64282),g=n(81585);const u=(0,i.withDispatch)((e=>{const t=e("eventin/global");return{refreshTagList:()=>t.invalidateResolution("getEventTagList")}})),v=(0,i.withSelect)((e=>{const t=e("eventin/global");return{tags:t.getEventTagList(),isLoading:t.isResolving("getEventTagList")}})),_=(0,l.compose)([v,u])((e=>{const{modalOpen:t,setModalOpen:n,refreshTagList:l,tags:i}=e,[u,v]=(0,r.useState)(!1),[_]=c.A.useForm(),{tagsData:E,setTagsData:h}=(0,r.useContext)(g.EventTagsContext)||{},f=E?.editData?.id;return(0,r.useEffect)((()=>{if(t){if(f){const{name:e,parent:t,description:n}=E?.editData;_.setFieldsValue({name:e,parent:t,description:n})}}else _.resetFields(),h&&h((e=>({...e,editData:null})))}),[t]),(0,a.createElement)(d.A,{title:(0,o.__)(f?"Edit Tag":"New Tag","eventin"),open:t,onCancel:()=>n(!1),cancelText:(0,o.__)("Cancel","eventin"),okText:f?(0,o.__)("Update Tag","eventin"):(0,o.__)("Add Tag","eventin"),onOk:async()=>{v(!0);try{await _.validateFields();const e=_.getFieldsValue();if(f){const t=E?.editData?.id;await p.A.eventTags.updateTag(t,e),(0,s.doAction)("eventin_notification",{type:"success",message:(0,o.__)("Successfully updated the tag!","eventin")})}else await p.A.eventTags.createTag(e),(0,s.doAction)("eventin_notification",{type:"success",message:(0,o.__)("Successfully created tag!","eventin")});_.resetFields(),l(),n(!1)}catch(e){console.error(e.message),(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{v(!1)}},confirmLoading:u,destroyOnClose:!0},(0,a.createElement)(c.A,{layout:"vertical",form:_},(0,a.createElement)("div",null,(0,a.createElement)(m.ks,{name:"name",label:(0,o.__)("Tag","eventin"),placeholder:(0,o.__)("Tag Name","eventin"),size:"middle",rules:[{required:!0,message:(0,o.__)("Tag Name is Required!","eventin")}],required:!0}),(0,a.createElement)(m.No,{label:(0,o.__)("Description","eventin"),name:"description",placeholder:(0,o.__)("Tag description","eventin")}))))}))},61397:(e,t,n)=>{n.d(t,{A:()=>h});var a=n(51609),l=n(92911),i=n(52741),r=n(11721),o=n(47767),s=n(52619),c=n(56427),m=n(27723),d=n(7638),p=n(79664),g=n(18062),u=n(27154),v=n(38224),_=n(54725),E=n(69815);function h(e){const{title:t,buttonText:n,onClickCallback:h}=e,{evnetin_ai_active:f,evnetin_pro_active:x}=window?.eventin_ai_local_data||{},A=(0,o.useNavigate)(),{pathname:y}=(0,o.useLocation)(),{doAction:b}=wp.hooks,k=["/events"!==y&&{key:"0",label:(0,m.__)("Event List","eventin"),icon:(0,a.createElement)(_.EventListIcon,{width:20,height:20}),onClick:()=>{A("/events")}},"/events/categories"!==y&&{key:"1",label:(0,m.__)("Event Categories","eventin"),icon:(0,a.createElement)(_.CategoriesIcon,null),onClick:()=>{A("/events/categories")}},"/events/tags"!==y&&{key:"2",label:(0,m.__)("Event Tags","eventin"),icon:(0,a.createElement)(_.TagIcon,null),onClick:()=>{A("/events/tags")}}],w=E.default.div`
		@media ( max-width: 360px ) {
			display: none;
		}
	`,C=(0,s.applyFilters)("eventin-ai-create-event-modal","eventin-ai");return(0,a.createElement)(c.Fill,{name:u.PRIMARY_HEADER_NAME},(0,a.createElement)(l.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,a.createElement)(g.A,{title:t}),(0,a.createElement)("div",{style:{display:"flex",alignItems:"center",gap:"10px"}},(0,a.createElement)(v.W,{onClick:()=>{b(f&&x?"eventin-ai-create-event-modal-visible":"eventin-ai-text-generator-modal",{visible:!0})}},(0,a.createElement)(_.AIGenerateIcon,null),(0,a.createElement)(v.o,null,(0,m.__)("Create an event with AI","eventin"))),(0,a.createElement)(d.Ay,{variant:d.zB,htmlType:"button",onClick:h,sx:{display:"flex",alignItems:"center",fontSize:"16px",fontWeight:600,borderRadius:"6px",height:"44px"}},(0,a.createElement)(_.PlusOutlined,null),n),(0,a.createElement)(i.A,{type:"vertical",style:{height:"44px",marginInline:"12px",top:"0"}}),(0,a.createElement)(l.A,{gap:12},(0,a.createElement)(r.A,{menu:{items:k},trigger:["click"],placement:"bottomRight",overlayClassName:"action-dropdown"},(0,a.createElement)(d.Ay,{variant:d.Vt,sx:{color:"#8C8C8C",height:"44px",lineHeight:"1",borderColor:"#747474",padding:"0px 12px"}},(0,a.createElement)(_.MoreIconOutlined,null))),(0,a.createElement)(w,null,(0,a.createElement)(p.A,null))))),(0,a.createElement)(C,{navigate:A}))}},38224:(e,t,n)=>{n.d(t,{W:()=>l,o:()=>i});var a=n(98869);const l=a.default.button`
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
		content: '';
		position: absolute;
		inset: -2px;
		border-radius: 6px;
		padding: 1px;
		background: linear-gradient( to left top, #fc8229, #e93da0, #404ef0 );
		-webkit-mask:
			linear-gradient( #fff 0 0 ) content-box,
			linear-gradient( #fff 0 0 );
		mask:
			linear-gradient( #fff 0 0 ) content-box,
			linear-gradient( #fff 0 0 );
		-webkit-mask-composite: xor;
		mask-composite: exclude;
	}

	&:hover {
		transform: translateY( -1px );
		background: rgba( 99, 102, 241, 0.04 );
	}

	&:active {
		transform: translateY( 0 );
	}

	svg {
		color: #ff69b4;
	}
`,i=a.default.span`
	background: linear-gradient(
		90deg,
		#fc8327 0%,
		#e83aa5 50.5%,
		#3a4ff2 100%
	);
	-webkit-background-clip: text;
	-webkit-text-fill-color: rgba( 0, 0, 0, 0 );
	background-clip: text;
`},81585:(e,t,n)=>{n.r(t),n.d(t,{EventTagsContext:()=>p,default:()=>u});var a=n(51609),l=n(29491),i=n(47143),r=n(86087),o=n(27723),s=n(31954),c=n(61397),m=n(35632),d=n(57770);const p=(0,r.createContext)(),g=(0,i.withSelect)((e=>{const t=e("eventin/global");return{tagList:t.getEventTagList(),isLoading:t.isResolving("getEventTagList")}})),u=(0,l.compose)(g)((function(e){const{tagList:t,isLoading:n}=e;let l=(0,d.A)(t,"name");l=(0,d.A)(l,"description");const[i,g]=(0,r.useState)({filter:{parentTag:null,searchTerm:null},editData:{},isModalOpen:!1}),u=e=>{g((t=>({...t,isModalOpen:e})))};return(0,a.createElement)(p.Provider,{value:{tagsData:i,setTagsData:g}},(0,a.createElement)("div",{className:"event-tags-wrapper"},(0,a.createElement)(c.A,{title:(0,o.__)("Event Tags","eventin"),onClickCallback:()=>u(!0),buttonText:(0,o.__)("New Tag","eventin")}),(0,a.createElement)(m.A,{isLoading:n,tagList:l}),(0,a.createElement)(s.A,{modalOpen:i.isModalOpen,setModalOpen:u})))}))},12387:(e,t,n)=>{n.d(t,{A:()=>u});var a=n(51609),l=n(19549),i=n(29491),r=n(47143),o=n(52619),s=n(27723),c=n(54725),m=n(7638),d=n(64282);const{confirm:p}=l.A,g=(0,r.withDispatch)((e=>{const t=e("eventin/global");return{refreshEventTags:()=>t.invalidateResolution("getEventTagList")}})),u=(0,i.compose)(g)((function(e){const{refreshEventTags:t,record:n}=e;return(0,a.createElement)(m.Ay,{variant:m.Vt,onClick:()=>{p({title:(0,s.__)("Are you sure?","eventin"),icon:(0,a.createElement)(c.DeleteOutlinedEmpty,null),content:(0,s.__)("Are you sure you want to delete this tag?","eventin"),okText:(0,s.__)("Delete","eventin"),okButtonProps:{type:"primary",danger:!0,classNames:"delete-btn"},centered:!0,onOk:async()=>{try{await d.A.eventTags.deleteTag(n.id),t(),(0,o.doAction)("eventin_notification",{type:"success",message:(0,s.__)("Successfully deleted the tag!","eventin")})}catch(e){console.error("Error deleting tag!",e),(0,o.doAction)("eventin_notification",{type:"error",message:(0,s.__)("Failed to delete the tag!","eventin")})}},onCancel(){}})}},(0,a.createElement)(c.DeleteOutlined,{width:"16",height:"16"}))}))},35530:(e,t,n)=>{n.d(t,{A:()=>s});var a=n(51609),l=n(86087),i=n(54725),r=n(7638),o=n(81585);function s(e){const{record:t}=e,{setTagsData:n}=(0,l.useContext)(o.EventTagsContext);return(0,a.createElement)(r.Ay,{variant:r.Vt,onClick:()=>{n((e=>({...e,editData:t,isModalOpen:!0})))}},(0,a.createElement)(i.EditOutlined,{width:"16",height:"16"}))}},12:(e,t,n)=>{n.d(t,{A:()=>o});var a=n(51609),l=n(90070),i=n(12387),r=n(35530);function o(e){const{record:t}=e;return(0,a.createElement)(l.A,{size:"small",className:"event-actions"},(0,a.createElement)(r.A,{record:t}),(0,a.createElement)(i.A,{record:t}))}},61401:(e,t,n)=>{n.d(t,{A:()=>r});var a=n(51609),l=n(27723),i=n(12);window.innerWidth;const r=[{title:(0,l.__)("Tags","eventin"),dataIndex:"name",key:"name",width:250,render:e=>(0,a.createElement)("p",{className:"event-title"},e)},{title:(0,l.__)("Description","eventin"),dataIndex:"description",key:"description",render:e=>(0,a.createElement)("span",{className:"author"},e)},{title:(0,l.__)("Action","eventin"),key:"action",width:120,render:(e,t)=>(0,a.createElement)(i.A,{record:t})}]},27857:(e,t,n)=>{n.d(t,{A:()=>u});var a=n(51609),l=n(92911),i=n(79888),r=n(86087),o=n(27723),s=n(54725),c=n(79351),m=n(62215),d=n(61149),p=n(81585),g=n(64282);const u=e=>{const{selectedTags:t,setSelectedTags:n}=e,{setTagsData:u}=(0,r.useContext)(p.EventTagsContext),v=!!t?.length;return(0,a.createElement)(d.O,{className:"filter-wrapper"},(0,a.createElement)(l.A,{justify:v?"space-between":"flex-end",align:"center"},(0,a.createElement)(l.A,{justify:"start",align:"center",gap:8},v&&(0,a.createElement)(c.A,{refreshListName:"getEventTagList",selectedCount:t?.length,callbackFunction:async()=>{const e=(0,m.A)(t);await g.A.eventTags.deleteTag(e),n([])},setSelectedRows:n})),!v&&(0,a.createElement)(i.A,{className:"event-filter-by-name",placeholder:(0,o.__)("Search by tag name","eventin"),size:"default",prefix:(0,a.createElement)(s.SearchIconOutlined,null),onChange:e=>{u((t=>({...t,filter:{...t.filter,searchTerm:e.target.value}})))},allowClear:!0})))}},35632:(e,t,n)=>{n.d(t,{A:()=>g});var a=n(51609),l=n(27723),i=n(86087),r=n(75063),o=n(16784),s=n(81585),c=n(27857),m=n(61401),d=n(78387),p=n(75093);function g(e){const{tagList:t,isLoading:n}=e,[g,u]=(0,i.useState)([]),[v,_]=(0,i.useState)([]),{tagsData:E}=(0,i.useContext)(s.EventTagsContext),h={selectedRowKeys:v,onChange:e=>{_(e)}};return(0,i.useEffect)((()=>{(()=>{let e=t;E?.filter?.searchTerm&&(e=e?.filter((e=>e?.name?.toLowerCase()?.includes(E?.filter?.searchTerm?.toLowerCase())))),u(e)})()}),[t,E]),n?(0,a.createElement)(d.f,{className:"eventin-page-wrapper"},(0,a.createElement)(r.A,{active:!0})):(0,a.createElement)(d.f,{className:"eventin-page-wrapper"},(0,a.createElement)("div",{className:"event-list-wrapper"},(0,a.createElement)(c.A,{selectedTags:v,setSelectedTags:_,tags:t}),(0,a.createElement)(o.A,{className:"eventin-data-table",columns:m.A,dataSource:g,rowSelection:h,rowKey:e=>e.id,scroll:window.screen.width<668&&{x:1200},sticky:{offsetHeader:80},pagination:{showTotal:(e,t)=>(0,a.createElement)(p.CustomShowTotal,{totalCount:e,range:t,listText:(0,l.__)("tags","eventin")})}})))}},78387:(e,t,n)=>{n.d(t,{f:()=>l});var a=n(69815);const l=a.default.div`
	background-color: #f4f6fa;
	padding: 12px 32px;
	min-height: 100vh;

	.ant-table-wrapper {
		padding: 20px;
		background-color: #fff;
	}

	.event-tags-wrapper {
		box-shadow: 0 2px 8px 0 rgba( 0, 0, 0, 0.15 );
		border-radius: 0 0 4px 4px;
	}

	.ant-table-wrapper {
		border-radius: 0 0 4px 4px;
	}

	.ant-table-thead {
		> tr {
			> th {
				background-color: transparent;
				padding-top: 10px;
				font-weight: 500;
				color: #747474;
				&:before {
					display: none;
				}
			}
		}
	}

	.event-title {
		color: #181818;
		font-size: 1rem;
		font-weight: 400;
		line-height: 24px;
		display: inline-flex;
		margin-bottom: 6px;
	}

	.event-location,
	.event-date-time {
		color: #858585;
		margin: 0;
		line-height: 1.4;
		font-size: 14px;
	}
	.event-date-time {
		display: flex;
		align-items: center;
		gap: 4px;
	}

	.event-location {
		margin-bottom: 4px;
	}

	.event-actions {
		.ant-btn {
			padding: 0;
			width: 28px;
			height: 28px;
			line-height: 1;
			display: flex;
			justify-content: center;
			align-items: center;
			border-color: #c9c9c9;
			color: #c9c9c9;
			background-color: #fff;
		}
	}

	.ant-tag {
		border-radius: 20px;
		font-size: 12px;
		font-weight: 400;
		padding: 4px 13px;
		min-width: 80px;
		text-align: center;
	}

	.ant-tag.event-category {
		background-color: transparent;
		font-size: 1rem;
		color: #181818;
		padding: 0;
		text-align: left;
	}

	.author {
		color: #181818;
		font-size: 1rem;
		text-transform: capitalize;
	}
`;a.default.div`
	padding: 18px 24px;
	background: #fff;
	border-radius: 4px 4px 0 0;
	border-bottom: 1px solid #ddd;

	.ant-form-item {
		margin-bottom: 0;
	}
	.ant-select-single {
		height: 36px;
		width: 120px !important;
	}

	.ant-picker {
		height: 36px;
	}
	.event-filter-by-name {
		height: 36px;
		border: 1px solid #ddd;
		max-width: 220px;

		input.ant-input {
			min-height: auto;
		}
	}
`},59682:(e,t,n)=>{n.r(t),n.d(t,{default:()=>zn});var a=n(51609),l=n(47767),i=n(56427),r=n(29491),o=n(47143),s=n(86087),c=n(52619),m=n(27723),d=n(17437),p=n(40372),g=n(47152),u=n(16370),v=n(92911),_=n(75063),E=n(11721),h=n(74353),f=n.n(h),x=n(54725),A=n(7638),y=n(80734),b=n(3493),k=n(57237),w=n(27154),C=n(10962),S=n(93997),T=n(64282),N=n(69815),z=n(6836);function L(e){const t={...e};(0,z.datesToString)(t);const n=t.location,{address:a,place_id:l,latitude:i,longitude:r,...o}=Object.assign({},n);return"offline"===t?.event_type?t.location={address:a?.toString()||"",place_id:l,latitude:i,longitude:r}:("custom_url"!==o?.integration&&(o.custom_url=""),t.location=o),t.fluent_crm=t.fluent_crm?"yes":"no",t.recurring_enabled=t.recurring_enabled&&"no"!==t.recurring_enabled?"yes":"no",t.virtual_product=t._virtual,t._virtual=t._virtual?"yes":"no",t.tax_status=t._tax_status,t._tax_status=t._tax_status?"none":"taxable",t?.organizer_type||(t.organizer_type="group"),t?.speaker_type||(t.speaker_type="group"),t?.faq?.[0]&&!t?.faq?.[0]?.etn_faq_title&&t.faq?.splice(0,1),t}const I=N.default.section`
	display: flex;
`,O=N.default.aside`
	border-right: 1px solid #0000000f;
	position: sticky;
	top: 100px;
	width: var( --etn-sidebar-width );
	padding: 24px 16px;
	overflow-y: auto;
	left: var( --etn-sidebar-left );
	height: calc( 100vh - var( --etn-fixed-header-height ) );

	&::-webkit-scrollbar {
		width: 3px;
		border-radius: 3px;
	}
	&::-webkit-scrollbar-thumb {
		border-radius: 3px;
		background-color: #d6d4d4;
	}
	&::-webkit-scrollbar-track {
		background-color: #f5f5f5;
	}

	.eventin-sidebar-list {
		li {
			a {
				font-size: 14px;
				text-decoration: none;
			}
			.ant-btn {
				font-size: 14px;
				line-height: 1;
				height: 36px;
				padding: 8px 12px;
				border-radius: 6px;
				display: flex;
				justify-content: flex-start;
				align-items: center;
				text-decoration: none;
				font-weight: 400;

				.ant-btn-icon svg {
					width: 20px;
					height: 20px;
				}
			}
		}
	}
`,F=N.default.h2`
	margin-bottom: 1em;
	margin-top: 0;
	color: #747474;
	font-size: 12px;
`,D=N.default.main`
	padding: 24px 16px;
	width: 732px;
	margin: 0 auto; // Default margin for larger screens

	@media ( max-width: 768px ) {
		// Applies styles for screens smaller than 768px
		margin: 0 10px;
	}
`,R=N.default.div`
	max-width: 500px;
	@media ( max-width: 768px ) {
		max-width: 350px;
	}
`,P=N.default.button`
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
		content: '';
		position: absolute;
		inset: -2px;
		border-radius: 6px;
		padding: 1px;
		background: linear-gradient( to left top, #fc8229, #e93da0, #404ef0 );
		-webkit-mask:
			linear-gradient( #fff 0 0 ) content-box,
			linear-gradient( #fff 0 0 );
		mask:
			linear-gradient( #fff 0 0 ) content-box,
			linear-gradient( #fff 0 0 );
		-webkit-mask-composite: xor;
		mask-composite: exclude;
	}

	&:hover {
		transform: translateY( -1px );
		background: rgba( 99, 102, 241, 0.04 );
	}

	&:active {
		transform: translateY( 0 );
	}

	svg {
		color: #ff69b4;
	}
`,V=N.default.span`
	background: linear-gradient(
		90deg,
		#fc8327 0%,
		#e83aa5 50.5%,
		#3a4ff2 100%
	);
	-webkit-background-clip: text;
	-webkit-text-fill-color: rgba( 0, 0, 0, 0 );
	background-clip: text;
`,{useBreakpoint:M}=p.Ay,B=(0,o.withDispatch)((e=>({setShouldRevalidateEventList:e("eventin/global").setRevalidateEventList}))),W=(0,r.compose)(B)((e=>{const{setShouldRevalidateEventList:t}=e,{form:n,saveButtonLoading:r,id:o,sourceData:p,isLoading:h}=(0,S.useEventSelectContext)(),{setSourceData:I}=(0,S.useEventDispatchContext)(),O="draft"===p?.status,[F,D]=(0,s.useState)(!O),[B,W]=(0,s.useState)(!1),[j,q]=(0,s.useState)(O);(0,s.useEffect)((()=>{const e="draft"===p?.status;D(!e),q(e)}),[p?.status]);const U=(0,l.useNavigate)(),{evnetin_ai_active:$,evnetin_pro_active:K}=window?.eventin_ai_local_data||{},G=n.getFieldValue("title"),Y=n.getFieldValue("start_date"),H=n.getFieldValue("start_time"),Q=`${f()(Y).format("ddd")}, ${(0,z.getWordpressFormattedDate)(Y)}, ${(0,z.getWordpressFormattedTime)(H)} `,J=p?.link,X={pointerEvents:"none",opacity:"0.5",cursor:"not-allowed"},Z=N.default.div`
		${e=>e.disabled&&X}
	`,ee=async e=>{const a={...L(p),status:e},l="publish"===e?(0,m.__)("Event published successfully!","eventin"):(0,m.__)("Event status changed to draft!","eventin");!F&&W(!0);try{const i=await T.A.events.updateEvent(o,a);I(i),D((e=>!e)),q((e=>!e)),t(!0),(0,c.doAction)("eventin_notification",{type:"success",message:l}),n.setFieldsValue({status:e,event_slug:i?.event_slug})}catch(e){console.error(e.message),(0,c.doAction)("eventin_notification",{type:"error",message:(0,m.__)("Couldn't change event status!","eventin"),description:`Reason: ${e?.response?.message}`})}finally{W(!1)}},te=[!j&&{label:(0,a.createElement)(A.Ay,{className:"eventin-event-detail-header-dropdown-button",variant:A.qy,size:"small",icon:(0,a.createElement)(x.DraftOutlined,{width:"16",height:"16"}),sx:{"&:hover":{color:"#6B2EE5",backgroundColor:"#f0eafc"}}},(0,m.__)("Draft","eventin")),key:"0",onClick:()=>ee("draft")},{label:(0,a.createElement)(A.i8,{className:"eventin-event-detail-header-dropdown-button",copy:J,variant:A.qy,icon:(0,a.createElement)(x.CloneOutlined,{width:"16",height:"16"}),size:"small",sx:{"&:hover":{color:"#6B2EE5"}}},(0,m.__)("Copy event link","eventin")),key:"1",className:"copy-event"},{label:(0,a.createElement)(A.Ay,{className:"eventin-event-detail-header-dropdown-button",variant:A.qy,onClick:()=>{(0,y.A)({title:(0,m.__)("Are you sure?","eventin"),content:(0,m.__)("Are you sure you want to delete this event?","eventin"),onOk:async()=>{try{await T.A.events.deleteEvent(o),t(!0),U("/")}catch(e){console.error("Error deleting event",e)}}})},icon:(0,a.createElement)(x.DeleteOutlined,{width:"16",height:"16"}),size:"small",sx:{color:"#FF4D4F"}},(0,m.__)("Delete event","eventin")),key:"2",className:"delete-event"}].filter(Boolean),ne=M().sm,ae=(0,c.applyFilters)("eventin-ai-edit-event-modal","eventin-ai");return(0,a.createElement)(i.Fill,{name:w.PRIMARY_HEADER_NAME},(0,a.createElement)(g.A,{gutter:[8,8],style:{alignItems:"center"},className:"eventin-event-details-header"},(0,a.createElement)(u.A,{xs:24,sm:12,xl:12},(0,a.createElement)(v.A,{align:"center",gap:16},(0,a.createElement)(A.Ay,{variant:A.Vt,icon:(0,a.createElement)(x.AngleLeftIcon,null),sx:{height:"36px",width:"36px",backgroundColor:"#fafafa",borderColor:"transparent",lineHeight:"1"},onClick:()=>U("/events")}),(0,a.createElement)(v.A,{vertical:!0},(0,a.createElement)(R,null,(0,a.createElement)(k.A,{ellipsis:{tooltip:G},sx:{margin:"0 0 5px",fontSize:"22px",lineHeight:"26px",color:"rgba(0, 0, 0, 0.80)",fontWeight:"600"}},h?(0,a.createElement)(_.A.Input,{active:!0,size:"small"}):G)),(0,a.createElement)(b.A,{sx:{fontSize:"16px",color:"rgba(0, 0, 0, 0.50)"}},h?(0,a.createElement)(_.A.Input,{active:!0,style:{height:"12px"}}):Q)))),(0,a.createElement)(u.A,{xs:24,sm:12,xl:12},(0,a.createElement)(g.A,{gutter:[8,8],style:{alignItems:"center",justifyContent:ne?"flex-end":"flex-start"}},(0,a.createElement)(u.A,null,(0,a.createElement)(P,{onClick:()=>{$&&K?(0,c.doAction)("eventin-ai-create-event-modal-visible",{visible:!0}):(0,c.doAction)("eventin-ai-text-generator-modal",{visible:!0})}},(0,a.createElement)(x.AIGenerateIcon,null),(0,a.createElement)(V,null,"Edit event with AI"))),(0,a.createElement)(u.A,null,(0,a.createElement)("a",{href:J,target:"_blank"},(0,a.createElement)(A.Ay,{variant:A.Qq,sx:{color:"#6B2EE5",fontWeight:"500",display:"flex",alignItems:"center",fontSize:"16px","&:hover":{backgroundColor:"transparent"}}},(0,a.createElement)(x.ExternalLinkOutlined,{width:"18",height:"18"}),ne&&(0,m.__)("Preview","eventin")))),(0,a.createElement)(u.A,null,(0,a.createElement)(d.mL,{styles:C.S}),(0,a.createElement)(E.A.Button,{trigger:["click"],placement:"bottomRight",overlayClassName:"action-dropdown",className:"ent-compact-btn eventin-event-details-header-dropdown",size:"large",arrow:!0,variant:"tertiary",icon:(0,a.createElement)(x.AngleDownIcon,null),onClick:()=>ee("publish"),loading:B,menu:{items:te},buttonsRender:([e,t])=>[(0,a.createElement)(Z,{disabled:F},e),t]},(0,m.__)("Publish Event","eventin"))),(0,a.createElement)(u.A,null,(0,a.createElement)(A.Ay,{loading:r,onClick:n.submit,variant:A.zB,sx:{fontSize:"16px"}},(0,m.__)("Save Changes","eventin")))))),(0,a.createElement)(ae,{id:o}))}));var j=n(32099),q=n(84976),U=n(22451),$=n(52171),K=n(62567),G=n(60742),Y=n(90070),H=n(43960),Q=n(49871),J=n(18138),X=n(48842),Z=n(10012);const ee=e=>(0,a.createElement)(g.A,{gutter:[16,10],justify:"center"},(0,a.createElement)(u.A,{md:24,xs:24},e.children)),te=N.default.div`
	position: relative;

	.etn-description-switcher {
		display: flex;
		gap: 10px;
		position: absolute;
		right: 0;
		top: 4px;
		z-index: 1;

		.active {
			background-color: #e5f3ff;
			color: #1890ff;
			border-color: #1890ff;
		}
	}
`,ne={width:"20px",height:"20px",padding:"10px"};var ae=n(82654);const le=N.default.div`
	margin: 30px 0;
	.ant-alert-message {
		font-size: 16px;
		color: #020617;
		font-weight: 500;
	}
	.ant-alert-description {
		font-size: 14px;
		color: #747474;
	}
	.etn-seo-alert {
		@media only screen and ( max-width: 520px ) {
			flex-direction: column;
		}
	}
	.ant-alert-action {
		@media only screen and ( max-width: 520px ) {
			margin-top: 20px;
		}
	}
	p {
		margin-bottom: 0;
		font-size: 14px;
		color: #747474;
	}
`,ie=({id:e})=>{const t=`${window.localized_data_obj.admin_url}post.php?post=${e}&action=edit`;return(0,a.createElement)(le,null,(0,a.createElement)(ae.A,{style:{border:"1px solid #C9C9C9",backgroundColor:"#fff",marginTop:"20px"},message:(0,m.__)("SEO Meta Information","eventin"),className:"etn-seo-alert",description:(0,a.createElement)("div",null,(0,a.createElement)("p",null,(0,m.__)("Set SEO meta information to enhance search engine visibility. For more information, please check the ","eventin")," ",(0,a.createElement)("a",{href:"https://support.themewinter.com/docs/plugins/plugin-docs/integration/how-to-integrate-seo-plugins/",target:"_blank",rel:"noopener noreferrer"},(0,m.__)("documentation","eventin")),"."),(0,a.createElement)("p",null,(0,a.createElement)("strong",null,(0,m.__)("Note:","eventin"))," ",(0,m.__)("Please ensure that an SEO plugin is installed.","eventin"))),action:(0,a.createElement)(A.Ay,{variant:A.Vt,size:"middle",sx:{color:"#6B2EE5",border:"1px solid #6B2EE5"},onClick:()=>window.open(t,"_blank")},(0,m.__)("Set SEO Meta","eventin"))}))},re=!!window.localized_data_obj.evnetin_pro_active,oe=(0,N.default)(K.A)`
	margin-bottom: 30px;
	.ant-collapse-content {
		display: none;
	}
`,se=(0,o.withSelect)((e=>{const t=e("eventin/global");return{settings:t.getSettings(),isLoading:t.isResolving("getSettings")}})),ce=(0,r.compose)(se)((e=>{const{settings:t,isLoading:n}=e,{sell_tickets:l}=t||{},{form:i,sourceData:r}=(0,S.useEventSelectContext)(),o=!!G.A.useWatch("_virtual",{form:i,preserve:!0}),s=!!G.A.useWatch("_tax_status",{form:i,preserve:!0}),c=[{key:"1",label:(0,a.createElement)(Y.A,{direction:"vertical",size:0},(0,a.createElement)(X.A,{sx:{color:"#334155",fontWeight:500}},(0,m.__)("Virtual Product","eventin")),(0,a.createElement)(X.A,{sx:{fontSize:"14px",color:"#747474"}},(0,m.__)("Register event as WooCommerce virtual product and let WooCommerce handle its behvaiour.","eventin"))),showArrow:!1,extra:(0,a.createElement)(G.A.Item,{name:"_virtual",valuePropName:"checked"},(0,a.createElement)(H.A,null))}],d=[{key:"1",label:(0,a.createElement)(Y.A,{direction:"vertical",size:0},(0,a.createElement)(X.A,{sx:{color:"#334155",fontWeight:500}},(0,m.__)("Tax Status","eventin")),(0,a.createElement)(X.A,{sx:{fontSize:"14px",color:"#747474"}},(0,m.__)("Disable tax status for this event.","eventin"))),showArrow:!1,extra:(0,a.createElement)(G.A.Item,{name:"_tax_status",valuePropName:"checked"},(0,a.createElement)(H.A,null))}];return n?(0,a.createElement)(_.A,{active:!0,style:{width:"100%"}}):(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header"},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("Additional page","eventin")),(0,a.createElement)(X.A,{className:"etn-section-description"},(0,m.__)("Add external links or an attendee page for a more detailed event experience.","eventin")),(0,a.createElement)(Q.A,{href:w.DOCUMENTATION_LINK,target:"_blank"},(0,m.__)(" Please check our documentation for more details.","eventin")))),(0,a.createElement)("div",{style:{margin:"30px 0px"}},(0,a.createElement)(G.A.Item,{name:"attende_page_link",style:{marginBottom:0},label:(0,a.createElement)(X.A,{sx:{color:"#334155",fontWeight:600,fontSize:"16px"}},(0,m.__)("Attendee Page URL","eventin")),tooltip:(0,m.__)("Page link where the details of the attendees of this event is located. NOTE: Only if attendee registration is enabled on the event settings.","eventin"),rules:[{type:"url",message:(0,m.__)("Please enter a valid URL / Link!","eventin")}]},re?(0,a.createElement)(Z.ks,{size:"large",placeholder:(0,m.__)("Enter Attendee Page URL","eventin")}):(0,a.createElement)(J.A,null)),(0,a.createElement)(G.A.Item,{label:(0,a.createElement)(X.A,{sx:{color:"#334155",fontWeight:600,fontSize:"16px"}},(0,m.__)("Event External Link","eventin")),tooltip:(0,m.__)("Add  an external link where the event details will redirect","eventin"),name:"external_link",style:{marginBottom:"10px"},rules:[{type:"url",message:(0,m.__)("Please enter a valid URL / Link!","eventin")}]},re?(0,a.createElement)(Z.ks,{placeholder:(0,m.__)("An external link where the event details will redirect","eventin"),size:"large"}):(0,a.createElement)(J.A,null)),"woocommerce"===l&&(0,a.createElement)(oe,{activeKey:o&&["1"],items:c,onChange:e=>{i.setFieldsValue({_virtual:Boolean(e.length)})},className:"eventin-collapse-wrapper"}),"woocommerce"===l&&(0,a.createElement)(oe,{activeKey:s&&["1"],items:d,onChange:e=>{i.setFieldsValue({_tax_status:Boolean(e.length)})},className:"eventin-collapse-wrapper"}),(0,a.createElement)(ie,{id:r?.id})))}));var me=n(13444),de=n(54861),pe=n(94790),ge=n(500),ue=n(79888),ve=n(78847),_e=n(37845);const Ee=(0,N.default)(K.A)`
	.ant-collapse-content-box {
		background-color: #f5f5f5;
	}
`,he=e=>{const{field:t,remove:n}=e,[l,i]=(0,s.useState)("New Schedule Topic"),[r,o]=(0,s.useState)(!1),c=[({getFieldValue:e})=>({validator(n,a){const l=e(["schedule_slot",t.name,"etn_shedule_end_time"]);if(l){const e=(0,z.getFormattedDateTime)({time:a});if((0,z.getFormattedDateTime)({time:l}).isBefore(e,"minutes"))return Promise.reject((0,m.__)("Start time must be before end time","eventin"))}return Promise.resolve()}})],d=[({getFieldValue:e})=>({validator(n,a){const l=e(["schedule_slot",t.name,"etn_shedule_start_time"]);if(l){const e=(0,z.getFormattedDateTime)({time:l});if((0,z.getFormattedDateTime)({time:a}).isBefore(e,"minutes"))return Promise.reject((0,m.__)("End time must be after start time","eventin"))}return Promise.resolve()}})],p=[{key:1,label:l,children:(0,a.createElement)(g.A,{gutter:[20,0]},(0,a.createElement)(u.A,{xs:24},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Topic Name","eventin"),name:[t.name,"etn_schedule_topic"]},(0,a.createElement)(Z.ks,{onChange:e=>i(e.target.value)}))),(0,a.createElement)(u.A,{md:12,xs:24},(0,a.createElement)(ve.A,{label:(0,m.__)("Start Time","eventin"),name:[t.name,"etn_shedule_start_time"],rules:c,dependencies:[["schedule_slot",t.name,"etn_shedule_end_time"]]})),(0,a.createElement)(u.A,{md:12,xs:24},(0,a.createElement)(ve.A,{label:(0,m.__)("End Time","eventin"),name:[t.name,"etn_shedule_end_time"],dependencies:[["schedule_slot",t.name,"etn_shedule_start_time"]],rules:d})),(0,a.createElement)(u.A,{md:12,xs:24},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Speaker","eventin"),name:[t.name,"speakers"]},(0,a.createElement)(_e.A,null))),(0,a.createElement)(u.A,{md:12,xs:24},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Location","eventin"),name:[t.name,"etn_shedule_room"]},(0,a.createElement)(Z.ks,{prefix:(0,a.createElement)(x.LocationOutlined,null)}))),(0,a.createElement)(u.A,{xs:24},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Description","eventin"),name:[t.name,"etn_shedule_objective"]},(0,a.createElement)(ue.A.TextArea,{className:"eventin-text-area",rows:3})))),extra:(0,a.createElement)(A.Ay,{variant:A.qy,icon:(0,a.createElement)(x.DeleteOutlined,{stroke:r?"#FF4D4F":"#747474"}),onClick:()=>n(t.name),onMouseEnter:()=>o(!0),onMouseLeave:()=>o(!1),size:"small"})}];return(0,a.createElement)(Ee,{expandIconPosition:"end",items:p,style:{margin:"10px 0px"}})},{useBreakpoint:fe}=p.Ay,xe=(0,o.withDispatch)((e=>{const t=e("eventin/global");return{refreshScheduleList:()=>t.invalidateResolution("getScheduleList")}})),Ae=(0,r.compose)(xe)((e=>{const{modalOpen:t,setModalOpen:n,refreshScheduleList:l}=e,[i]=G.A.useForm(),[r,o]=(0,s.useState)(!1),c=fe().sm;return(0,a.createElement)(ge.A,{title:(0,m.__)("New Schedule","eventin"),open:t,onCancel:()=>n(!1),cancelText:(0,m.__)("Cancel","eventin"),okText:(0,m.__)("Add Schedule","eventin"),onOk:async()=>{o(!0);try{await i.validateFields();const e=i.getFieldsValue();e.date=(0,z.dateFormatter)(e.date);const t=await T.A.schedule.createSchedule(e);if(!t?.id)throw new Error("Couldn't create schedule properly!");n(!1),l()}catch(e){console.error("Couldn't create schedule!"),console.error(e)}finally{o(!1)}},confirmLoading:r,style:c?{maxWidth:"600px",width:"100%"}:{},destroyOnClose:!0,styles:{body:{height:"70vh",overflowY:"auto",overflowX:"hidden",scrollbarWidth:"thin"}}},(0,a.createElement)(G.A,{layout:"vertical",form:i,initialValues:{schedule_topics:[{}]},scrollToFirstError:!0,preserve:!1,size:"large"},(0,a.createElement)(g.A,{gutter:[16,0]},(0,a.createElement)(u.A,{xs:24,md:12},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Program Title","eventin"),name:"program_title",rules:[{required:!0,message:(0,m.__)("Program title is required!","eventin")}]},(0,a.createElement)(Z.ks,{placeholder:(0,m.__)("Program title","eventin"),size:"large"}))),(0,a.createElement)(u.A,{xs:24,md:12},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Date","eventin"),name:"date",rules:[{required:!0,message:(0,m.__)("Program date is required!","eventin")}]},(0,a.createElement)(de.A,{size:"large",minDate:f()(new Date),style:{width:"100%"},format:(0,z.getDateFormat)()})))),(0,a.createElement)(g.A,{gutter:[16,0]},(0,a.createElement)(u.A,{xs:24},(0,a.createElement)(v.A,{align:"center"},(0,a.createElement)("p",{style:{margin:"5px 0px",fontSize:"16px",fontWeight:500}},(0,m.__)("Schedule Topics","eventin")),(0,a.createElement)(j.A,{title:(0,m.__)("Give attendees a sneak peek at the key topics your event will address.","eventin")},(0,a.createElement)("span",{style:{marginLeft:"7px"}},(0,a.createElement)(x.InfoCircleOutlined,{width:15,height:15})))),(0,a.createElement)(G.A.List,{name:"schedule_slot",label:(0,m.__)("Schedule Topics","eventin")},((e,{add:t,remove:n},{errors:l})=>(0,a.createElement)(a.Fragment,null,e?.length<1&&(0,a.createElement)(X.A,{sx:{display:"block",marginBottom:"8px"}},(0,m.__)("No topic added yet!","eventin")),e.map((e=>(0,a.createElement)(he,{key:e.key,field:e,remove:n}))),(0,a.createElement)(G.A.Item,null,(0,a.createElement)(pe.A,null,(0,a.createElement)(A.Ay,{className:"eventin-add-button",variant:A.Rm,onClick:()=>t(),icon:(0,a.createElement)(x.PlusOutlined,{width:16,height:16}),size:"middle",sx:{width:"100%",fontSize:"14px",fontWeight:500,height:"36px"}},(0,m.__)("Add Topic","eventin")))))))))))})),ye=(0,o.withSelect)((e=>{const t=e("eventin/global");return{scheduleList:t.getScheduleList(),isLoading:t.isResolving("getScheduleList")}})),be=(0,r.compose)(ye)((e=>{const{scheduleList:t}=e,[n,l]=(0,s.useState)(!1);return(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header"},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("Schedule","eventin")),(0,a.createElement)(X.A,null,(0,m.__)("Plan your event flow and tell event-goers why they should come. Add details that highlight what makes it unique.","eventin")),(0,a.createElement)(Q.A,{href:w.DOCUMENTATION_LINK,target:"_blank"},(0,m.__)(" Please check our documentation for more details.","eventin")))),(0,a.createElement)(k.A,{level:4,style:{display:"flex",alignItems:"center",gap:"10px",fontWeight:600,margin:"60px 0  25px",fontSize:"18px"}},(0,a.createElement)(x.MicIcon,null)," ",(0,m.__)("Event schedule","eventin")),(0,a.createElement)(a.Fragment,null,(0,a.createElement)(G.A.Item,{label:(0,m.__)("Schedule group","eventin"),name:"schedules",tooltip:(0,m.__)("Select the schedule group for the event","eventin")},(0,a.createElement)(me.A,{placeholder:(0,m.__)("Select Schedule","eventin"),options:t,fieldNames:{value:"id",label:(0,m.__)("program_title","eventin")},mode:"multiple"},(0,a.createElement)(A.yl,{onClick:()=>l(!0)},(0,m.__)("Add New Schedule","eventin"))))),(0,a.createElement)(Ae,{modalOpen:n,setModalOpen:l}))}));var ke=n(62317),we=n(75642),Ce=n(72397),Se=n(6827),Te=n(13012),Ne=n(75613),ze=n(59292),Le=n(43829),Ie=n(68559);const Oe=e=>{const{options:t,children:n,dropdownRenderStyle:l,...i}=e;return(0,a.createElement)(Ie.A,{treeData:t,multiple:!0,size:"large",treeDefaultExpandAll:!0,treeCheckable:!0,autoClearSearchValue:!0,showSearch:!0,dropdownRender:e=>(0,a.createElement)(a.Fragment,null,(0,a.createElement)("div",{style:{...l||{margin:"10px 0px",borderRadius:"4px"}}},e),(0,a.createElement)("hr",{style:{margin:"5px !important",opacity:.5,padding:"0px 4px"}}),n),...i})};var Fe=n(25814),De=n(57770),Re=n(70034),Pe=n(31954),Ve=n(38181);const{useBreakpoint:Me}=p.Ay,Be=({settings:e})=>{const[t,n]=(0,s.useState)(!1),{form:l,sourceData:i}=(0,S.useEventSelectContext)(),r="draft"===i?.status,o=e?.event_url_editable,c=i?.link,d=Me().sm;return(0,s.useEffect)((()=>{t&&(n(!1),l.setFieldsValue({link:c}))}),[c]),(0,a.createElement)(v.A,{style:{width:"100%",gap:"4px"},wrap:"wrap",className:"eventin-event-link-edit-wrapper"},!o||r?(0,a.createElement)(G.A.Item,{name:"link",style:{flex:1},rules:[{required:!0,message:(0,m.__)("Event link is required!","eventin")}]},(0,a.createElement)(ue.A,{prefix:(0,a.createElement)(a.Fragment,null),disabled:!0,type:"url"})):(0,a.createElement)(G.A.Item,{name:t?"event_slug":"link",style:{flex:1},rules:[{required:!0,message:(0,m.__)("Event link is required!","eventin")}]},(0,a.createElement)(ue.A,{prefix:(0,a.createElement)(a.Fragment,null),addonAfter:d?(0,a.createElement)(Ve.A,{onChange:e=>{n(e.target.checked)},checked:t,disabled:r},(0,m.__)("Edit","eventin")):null,disabled:!t,type:"url"})),(0,a.createElement)(j.A,{title:(0,m.__)("Click to Copy Event Link","eventin")},(0,a.createElement)(A.i8,{copy:i?.link,variant:A.Vt,sx:{color:"#747474",lineHeight:"1"},style:!d&&{height:"46px"}},(0,a.createElement)(x.CopyOutlined,{width:16,height:16}))))};var We=n(75093);const je=({form:e})=>{const t=G.A.useWatch("excerpt_enable",e);return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(v.A,{gap:5,align:"center"},(0,a.createElement)(G.A.Item,{name:"excerpt_enable",valuePropName:"checked",style:{width:"100%",lineHeight:"1"}},(0,a.createElement)(Ve.A,{onChange:t=>{e.setFieldValue("excerpt_enable",t.target.checked)},style:{fontWeight:600,color:"#334155"},className:"post-excerpt-checkbox"},(0,m.__)("Add an excerpt","eventin"),(0,a.createElement)(j.A,{title:(0,m.__)("A post excerpt provides a brief summary of content","eventin")},(0,a.createElement)("span",null," ",(0,a.createElement)(x.InfoCircleOutlined,{width:15,height:15})))))),t&&(0,a.createElement)(We.TextAreaInput,{form:e,name:"excerpt",className:"post-excerpt-description",label:(0,m.__)("Excerpt","eventin"),placeholder:(0,m.__)("Enter post excerpt description","eventin")}))};var qe=n(27174);const Ue=(0,o.withSelect)((e=>{const t=e("eventin/global");return{settings:t.getSettings(),eventCategories:t.getEventCategories(),eventTags:t.getEventTagList(),isLoading:t.isResolving("getEventCategories")}})),$e=(0,r.compose)(Ue)((e=>{const{settings:t,eventCategories:n,eventTags:l}=e,{form:i,sourceData:r}=(0,S.useEventSelectContext)(),{doAction:o}=wp.hooks,[c,d]=(0,s.useState)(!1),[p,v]=(0,s.useState)(!1),[_,E]=(0,s.useState)("classic"),h=(0,De.A)(n,"name"),f=(0,De.A)(l,"name"),[y,b]=(0,s.useState)(r?.edit_with_elementor),w=r?.elementor_supported,C=G.A.useWatch("description",{form:i,preserve:!0}),N=(e="event-creation-description",t="short")=>{o("eventin-ai-text-generator-modal",{visible:!0,tokenType:t,contextId:e})},z=()=>{i.setFieldsValue({description:e.response})},L=()=>(0,a.createElement)("div",{style:{display:"flex",alignItems:"center",gap:"6px"}},(0,a.createElement)("h4",{style:{margin:"0px"}},(0,m.__)("Description","eventin")),(0,a.createElement)(Fe.A,{form:i,name:"description",onClick:N,contextId:"event-creation-description",style:{position:"static",top:"0",right:"0"},handleChange:z}));return(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header"},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("Basic Information","eventin")),(0,a.createElement)(X.A,{className:"etn-section-description"},(0,m.__)("Fill in the key details about your event.","eventin")))),(0,a.createElement)(k.A,{level:4,className:"etn-section-subtitle"},(0,a.createElement)(x.ListOutlinedBorder,null)," ",(0,m.__)("Basic Information","eventin")),(0,a.createElement)(Z.QN,{label:(0,m.__)("Event Title","eventin"),name:"title",required:!0,contextId:"eventin-event-title",tokenType:"short",form:i,rules:[{required:!0,message:(0,m.__)("Event title is required","eventin")}]}),(0,a.createElement)(Be,{settings:t}),(0,a.createElement)(te,null,(0,a.createElement)("div",{className:"etn-description-switcher"},(0,a.createElement)(j.A,{title:(0,m.__)("Classic Editor","eventin")},(0,a.createElement)(A.Ay,{variant:A.Vt,className:"classic"===_?"active":"",onClick:()=>E("classic"),sx:ne},(0,a.createElement)(x.NoteIcon,{width:"16",height:"16"}))),(0,a.createElement)(j.A,{title:(0,m.__)("Gutenberg Editor","eventin")},(0,a.createElement)(A.Ay,{variant:A.Vt,className:"gutenberg"===_?"active":"",onClick:()=>E("gutenberg"),sx:ne},(0,a.createElement)(x.GutenbergIcon,{width:"16",height:"16"}))),w&&(0,a.createElement)(Se.A,null)),"gutenberg"===_&&(0,a.createElement)(we.w,null,(0,a.createElement)(G.A.Item,{name:"description",label:(0,a.createElement)(L,null),className:"description etn-description-block-editor "+(y?"elementor-data-loaded":""),tooltip:(0,m.__)("Describe your event in detail to grab the attention and excite potential attendees.","eventin"),help:(0,a.createElement)(Te.A,{ElBtnSubmit:async()=>b(await T.A.events.backToWordPress(r?.id)),edit_with_elementor:y})},(0,a.createElement)(ke.A,null))),"classic"===_&&(0,a.createElement)(G.A.Item,{name:"description",label:(0,a.createElement)(L,null)},(0,a.createElement)(qe.A,{value:C,onChange:e=>{i.setFieldValue("description",e)},height:300,hasMedia:!0,useExtendStyles:!0}))),(0,a.createElement)(je,{form:i}),(0,a.createElement)(Ce.A,{dateRange:"date_range",startDate:"start_date",startTime:"start_time",endDate:"end_date",endTime:"end_time",form:i}),(0,a.createElement)(G.A.Item,{name:"timezone",label:"Timezone"},(0,a.createElement)(Le.A,null)),(0,a.createElement)(ze.A,{form:i,unitName:"recurrence_freq",intervalName:"recurrence_daily_interval",checkBoxName:"recurring_enabled",eventEnds:"recurrence_ends_on"}),(0,a.createElement)(k.A,{level:4,className:"etn-section-subtitle"},(0,a.createElement)(x.LocationOutlinedAlt,{width:22,height:22})," ",(0,m.__)("Event Type","eventin")),(0,a.createElement)(Ne.A,{form:i,sourceData:r}),(0,a.createElement)(g.A,{gutter:[16,0]},(0,a.createElement)(u.A,{md:12,sm:24,xs:24},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Categories","eventin"),name:"categories",tooltip:(0,m.__)("Choose the best category (or categories) to describe and help people find your event.","eventin")},(0,a.createElement)(Oe,{options:h,placeholder:(0,m.__)("Select Categories","eventin"),fieldNames:{value:"id",label:"name"},filterTreeNode:(e,t)=>t?.name?.toLowerCase()?.includes(e?.toLowerCase()),mode:"multiple",maxTagCount:"responsive"},(0,a.createElement)(A.yl,{onClick:()=>d(!0)},(0,m.__)("Add new category","eventin"))))),(0,a.createElement)(u.A,{md:12,sm:24,xs:24},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Tags","eventin"),name:"tags",tooltip:(0,m.__)("Use relevant keywords to help people find your event through specific searches.","eventin")},(0,a.createElement)(Oe,{mode:"multiple",placeholder:(0,m.__)("Select Tags","eventin"),options:f,size:"large",fieldNames:{value:"id",label:"name"},filterTreeNode:(e,t)=>t?.name?.toLowerCase()?.includes(e?.toLowerCase()),maxTagCount:"responsive"},(0,a.createElement)(A.yl,{onClick:()=>v(!0)},(0,m.__)("Add new tag","eventin")))))),(0,a.createElement)(Re.A,{modalOpen:c,setModalOpen:d}),(0,a.createElement)(Pe.A,{modalOpen:p,setModalOpen:v}))}));var Ke=n(78056),Ge=n(41276),Ye=n(99506);const He=(0,o.withSelect)((e=>{const t=e("eventin/global");return{settings:t.getSettings(),isLoading:t.isResolving("getSettings")}})),Qe=(0,r.compose)(He)((function(e){const{settings:t,isLoading:n}=e,l=!!window.localized_data_obj.evnetin_pro_active,i="on"===t?.attendee_registration;return n?(0,a.createElement)(_.A,{active:!0}):(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header",style:{marginBlockEnd:"40px"}},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("Extra Fields","eventin")),(0,a.createElement)(X.A,{className:"etn-section-description"},(0,m.__)("Add extra fields to collect additional information from attendees.","eventin")))),(0,a.createElement)("div",{style:{margin:"50px 0px"}},l?i?(0,a.createElement)(Ge.A,{formContextDispatcher:(0,S.useEventDispatchContext)(),formContext:(0,S.useEventSelectContext)(),settings:t}):(0,a.createElement)(Ke.A,{title:(0,m.__)("Enable Attendee Registration","eventin"),description:(0,m.__)('Please enable "attendee registration" on the event settings to create an Extra Field.',"eventin"),buttonText:(0,m.__)("Go to Attendee","eventin"),redirectUrl:`${window.localized_data_obj.site_url}/wp-admin/admin.php?page=eventin#/settings/event-settings/attendees`}):(0,a.createElement)(Ye.A,null)))})),Je=e=>{const{field:t,remove:n,expandedKey:l,setExpandedKey:i}=e,{form:r}=(0,S.useEventSelectContext)(),o=[{key:1,label:G.A.useWatch(["faq",t.name,"etn_faq_title"],r)||"FAQ Title",children:(0,a.createElement)(g.A,{gutter:[16,0]},(0,a.createElement)(u.A,{xs:24},(0,a.createElement)(Z.ks,{name:[t.name,"etn_faq_title"],label:(0,m.__)("FAQ Title","eventin"),style:{fontWeight:500},rules:[{required:!0,message:(0,m.__)("FAQ title is required!","eventin")}],required:!0})),(0,a.createElement)(u.A,{xs:24},(0,a.createElement)(Z.No,{label:(0,m.__)("Description","eventin"),name:[t.name,"etn_faq_content"],placeholder:(0,m.__)("FAQ description","eventin")}))),extra:(0,a.createElement)(A.Ay,{variant:A.qy,icon:(0,a.createElement)(x.DeleteOutlined,null),onClick:()=>n(t.name),size:"small"})}];return(0,a.createElement)(K.A,{expandIconPosition:"end",items:o,style:{margin:"10px 0px"},accordion:!0,defaultActiveKey:0===t?.key&&[1],onChange:e=>{i(e?.length?t?.key:null)},activeKey:l===t?.key&&["1"]})};function Xe(e){const{form:t}=(0,S.useEventSelectContext)(),n=!!window.localized_data_obj.evnetin_pro_active,l=G.A.useWatch("fluent_crm",t),i=[{key:"1",label:(0,a.createElement)(Y.A,{direction:"vertical",size:0},(0,a.createElement)(X.A,{sx:{color:"#334155",fontWeight:500}},(0,m.__)("Integrate fluent CRM","eventin")),(0,a.createElement)(X.A,{sx:{fontSize:"14px",color:"#747474"}},(0,m.__)("Enable Fluent CRM integration with this event.","eventin"))),children:(0,a.createElement)(G.A.Item,{label:(0,m.__)("Fluent Webhook","eventin"),name:"fluent_crm_webhook",rules:[{type:"url",message:(0,m.__)("Please enter a valid URL","eventin")}]},(0,a.createElement)(Z.ks,{size:"large",placeholder:(0,m.__)("Enter fluent web hook URL","eventin")})),showArrow:!1,extra:(0,a.createElement)(G.A.Item,{name:"fluent_crm",valuePropName:"checked"},n?(0,a.createElement)(H.A,null):(0,a.createElement)(We.ProButton,null))}];return(0,a.createElement)(v.A,{vertical:!0,gap:20},(0,a.createElement)(K.A,{activeKey:n&&l&&["1"],items:i,onChange:e=>{t.setFieldsValue({fluent_crm:Boolean(e.length)})},collapsible:n?"header":"disabled",className:"eventin-collapse-wrapper"}))}const Ze=(0,o.withSelect)((e=>({}))),et=(0,r.compose)(Ze)((e=>(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header"},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("Integrations","eventin")),(0,a.createElement)(X.A,null,(0,m.__)("Integrations act as bridges, connecting your event to a network of valuable tools.","eventin")),(0,a.createElement)(Q.A,{href:w.DOCUMENTATION_LINK,target:"_blank"},(0,m.__)(" Please check our documentation for more details.","eventin")))),(0,a.createElement)("div",{style:{margin:"35px 0"}},(0,a.createElement)(v.A,{vertical:!0,gap:20},(0,a.createElement)(Xe,null))))));var tt=n(34932);function nt(){const e="1"===localized_data_obj?.evnetin_pro_active;return(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:4,className:"etn-section-subtitle"},(0,a.createElement)(x.ColorPrintIcon,null)," ",(0,m.__)("Color Select","eventin")),(0,a.createElement)(g.A,{gutter:[16,0]},(0,a.createElement)(u.A,{xs:24,sm:12},(0,a.createElement)(G.A.Item,{name:"calendar_bg",label:(0,m.__)("Background Color For Calendar","eventin"),getValueFromEvent:e=>"#"+e.toHex(),tooltip:(0,m.__)("The background color is applied to the area where the calendar is displayed.","eventin")},e?(0,a.createElement)(tt.A,{sx:{width:"100%",justifyContent:"flex-start"}}):(0,a.createElement)(J.A,null))),(0,a.createElement)(u.A,{xs:24,sm:12},(0,a.createElement)(G.A.Item,{name:"calendar_text_color",label:(0,m.__)("Text Color For Calendar","eventin"),getValueFromEvent:e=>"#"+e.toHex(),tooltip:(0,m.__)("The text color option is used where the event calendar is displayed.","eventin")},e?(0,a.createElement)(tt.A,{sx:{width:"100%",justifyContent:"flex-start"}}):(0,a.createElement)(J.A,null)))))}var at=n(16032);function lt(){const{form:e}=(0,S.useEventSelectContext)();return(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:4,className:"etn-section-subtitle"},(0,a.createElement)(x.SocialLinksIcon,null)," ",(0,m.__)("Social Links","eventin")),(0,a.createElement)(g.A,{gutter:[16,0]},(0,a.createElement)(u.A,{xs:24},(0,a.createElement)(v.A,{align:"center"},(0,a.createElement)("p",{style:{margin:"10px 0px",fontSize:"16px",fontWeight:600,color:"#444444"}},(0,m.__)("Social","eventin")),(0,a.createElement)(j.A,{title:(0,m.__)("Promote your event by adding links to your social media profiles.","eventin")},(0,a.createElement)("span",{style:{marginLeft:"7px"}},(0,a.createElement)(x.InfoCircleOutlined,{width:15,height:15})))),(0,a.createElement)(at.A,{name:"event_socials",label:"{ __( 'Social', 'eventin' ) }",form:e,sx:{height:"180px"}}))))}var it=n(91807);function rt(e){const{form:t}=(0,S.useEventSelectContext)();return(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:4,className:"etn-section-subtitle"},(0,a.createElement)(x.ImageOutlineIcon,null)," ",(0,m.__)("Images","eventin")),(0,a.createElement)(g.A,{gutter:[20,20]},(0,a.createElement)(u.A,{xs:24,md:8},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Event logo","eventin"),name:"event_logo",tooltip:(0,m.__)("Upload your event logo","eventin")},(0,a.createElement)(it.ng,{form:t,name:"event_logo",buttonText:(0,m.__)("Upload Logo","eventin")}))),(0,a.createElement)(u.A,{xs:24,md:8},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Event Banner","eventin"),name:"banner_image",tooltip:(0,m.__)("Upload your event banner image","eventin")},(0,a.createElement)(it.ng,{form:t,name:"event_banner",buttonText:(0,m.__)("Upload Banner","eventin")})))))}var ot=n(5019);const st=(0,N.default)(K.A)`
	.ant-collapse-content {
		display: none;
	}
`,ct=e=>{const t=!!window.localized_data_obj.evnetin_pro_active,{form:n}=(0,S.useEventSelectContext)(),l=G.A.useWatch("rsvp_settings",n),i=l?.enable_rsvp_form,r=l?.show_rsvp_attendee,o=l?.limit_rsvp_capcatiy,s=l?.etn_disable_purchase_form,c=l?.rsvp_display_form_only_for_logged_in_users;if(!i||!t)return;const d=[{key:"1",label:(0,a.createElement)(Y.A,{direction:"vertical",size:0},(0,a.createElement)(X.A,{sx:{color:"#334155",fontWeight:500}},(0,m.__)("Display Attendee List","eventin")),(0,a.createElement)(X.A,{sx:{fontSize:"14px",color:"#747474"}},(0,m.__)("Do you want to display going attendee list?","eventin"))),children:(0,a.createElement)(G.A.Item,{label:(0,m.__)("Attendee List Limit","eventin"),name:["rsvp_settings","attendee_list_limit"]},(0,a.createElement)(ot.A,{placeholder:(0,m.__)("Keep this blank to show all attendee","eventin")})),showArrow:!1,extra:(0,a.createElement)(G.A.Item,{name:["rsvp_settings","show_rsvp_attendee"],valuePropName:"checked"},(0,a.createElement)(H.A,null))}],p=[{key:"1",label:(0,a.createElement)(Y.A,{direction:"vertical",size:0},(0,a.createElement)(X.A,{sx:{color:"#334155",fontWeight:500}},(0,m.__)("Limit RSVP Attendee Capacity.","eventin")),(0,a.createElement)(X.A,{sx:{fontSize:"14px",color:"#747474"}},(0,m.__)("Do you want to limit RSVP attendence capacity?","eventin"))),children:(0,a.createElement)(g.A,{gutter:[16,10]},(0,a.createElement)(u.A,{xs:24},(0,a.createElement)(G.A.Item,{label:(0,m.__)("RSVP capacity attendee limit","eventin"),name:["rsvp_settings","etn_rsvp_limit_amount"]},(0,a.createElement)(ot.A,{placeholder:(0,m.__)("Total attendee for this RSVP","eventin")}))),(0,a.createElement)(u.A,{xs:24,md:24},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Maximum attendee per response","eventin"),name:["rsvp_settings","rsvp_attendee_form_limit"]},(0,a.createElement)(ot.A,{defaultValue:1,placeholder:(0,m.__)("Total attendees registration for a single response","eventin")}))),(0,a.createElement)(u.A,{xs:24,md:24},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Attendance required to start event","eventin"),name:["rsvp_settings","rsvp_miminum_attendee_to_start"]},(0,a.createElement)(ot.A,{placeholder:(0,m.__)("Minimum attendees to start a event","eventin")})))),showArrow:!1,extra:(0,a.createElement)(G.A.Item,{name:["rsvp_settings","limit_rsvp_capcatiy"],valuePropName:"checked"},(0,a.createElement)(H.A,null))}],_=[{key:"1",label:(0,a.createElement)(Y.A,{direction:"vertical",size:0},(0,a.createElement)(X.A,{sx:{color:"#334155",fontWeight:500}},(0,m.__)("Disable Purchase Form","eventin")),(0,a.createElement)(X.A,{sx:{fontSize:"14px",color:"#747474"}},(0,m.__)("Disable selling for this event?","eventin"))),showArrow:!1,extra:(0,a.createElement)(G.A.Item,{name:["rsvp_settings","etn_disable_purchase_form"],valuePropName:"checked"},(0,a.createElement)(H.A,null))}],E=[{key:"1",label:(0,a.createElement)(Y.A,{direction:"vertical",size:0},(0,a.createElement)(X.A,{sx:{color:"#334155",fontWeight:500}},(0,m.__)("Display Form Only for Logged in Users","eventin")),(0,a.createElement)(X.A,{sx:{fontSize:"14px",color:"#747474"}},(0,m.__)("Display the RSVP form only for logged in users.","eventin"))),showArrow:!1,extra:(0,a.createElement)(G.A.Item,{name:["rsvp_settings","rsvp_display_form_only_for_logged_in_users"],valuePropName:"checked"},(0,a.createElement)(H.A,null))}];return(0,a.createElement)(v.A,{vertical:!0,gap:20},(0,a.createElement)(K.A,{activeKey:r&&["1"],items:d,onChange:e=>{n.setFieldValue(["rsvp_settings","show_rsvp_attendee"],Boolean(e.length))},className:"eventin-collapse-wrapper"}),(0,a.createElement)(st,{activeKey:c&&["1"],items:E,onChange:e=>{n.setFieldValue(["rsvp_settings","rsvp_display_form_only_for_logged_in_users"],Boolean(e.length))},className:"eventin-collapse-wrapper"}),(0,a.createElement)(K.A,{activeKey:o&&["1"],items:p,onChange:e=>{n.setFieldValue(["rsvp_settings","limit_rsvp_capcatiy"],Boolean(e.length))},className:"eventin-collapse-wrapper"}),(0,a.createElement)(st,{activeKey:s&&["1"],items:_,onChange:e=>{n.setFieldValue(["rsvp_settings","etn_disable_purchase_form"],Boolean(e.length))},className:"eventin-collapse-wrapper"}))},mt=(0,o.withSelect)((e=>{const t=e("eventin/global");return{settings:t.getSettings(),isLoading:t.isResolving("getSettings")}})),dt=(0,r.compose)(mt)((function(e){const{settings:t,isLoading:n}=e,{form:l}=(0,S.useEventSelectContext)(),i=!!window.localized_data_obj.evnetin_pro_active,r=G.A.useWatch(["rsvp_settings"],l),o=i&&r?.enable_rsvp_form,s="on"===t?.modules?.rsvp,c=[{label:(0,m.__)("Going","eventin"),value:"going"},{label:(0,m.__)("Not Going","eventin"),value:"not_going"},{label:(0,m.__)("Maybe","eventin"),value:"maybe"}],d=[{key:"1",label:(0,a.createElement)(Y.A,{direction:"vertical",size:0},(0,a.createElement)(X.A,{sx:{color:"#334155",fontWeight:500}},(0,m.__)("Enable RSVP?","eventin")),(0,a.createElement)(X.A,{sx:{fontSize:"14px",color:"#747474"}},(0,m.__)("Do you want to enable RSVP then Disable selling for this event?","eventin"))),children:(0,a.createElement)(G.A.Item,{label:(0,m.__)("RSVP Response","eventin"),name:["rsvp_settings","rsvp_form_type"],style:{fontWeight:400}},(0,a.createElement)(Ve.A.Group,{options:c,className:"rsvp-response-checkbox"})),showArrow:!1,extra:(0,a.createElement)(G.A.Item,{name:["rsvp_settings","enable_rsvp_form"],valuePropName:"checked"},i?(0,a.createElement)(H.A,null):(0,a.createElement)(We.ProButton,null))}];return n?(0,a.createElement)(_.A,{active:!0}):(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header",style:{marginBlockEnd:"40px"}},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("RSVP","eventin")),(0,a.createElement)(X.A,null,(0,m.__)("Allow attendees to indicate their attendance status.","eventin")))),s?(0,a.createElement)(a.Fragment,null,(0,a.createElement)("div",{style:{marginBlockEnd:"20px"}},(0,a.createElement)(K.A,{activeKey:o&&["1"],items:d,onChange:e=>{const t=Boolean(e.length);l.setFieldValue(["rsvp_settings","enable_rsvp_form"],t)},collapsible:i?"header":"disabled",className:"eventin-collapse-wrapper"})),(0,a.createElement)(ct,null)):(0,a.createElement)(Ke.A,{title:(0,m.__)("Enable RSVP Module","eventin"),description:(0,m.__)("It allows you to add RSVP at your upcoming events and grab users attention easily.","eventin"),buttonText:(0,m.__)("Go to Addons","eventin"),redirectUrl:`${window.localized_data_obj.site_url}/wp-admin/admin.php?page=eventin#/add-ons`}))}));var pt=n(67313),gt=n(428),ut=n(80560),vt=n(3087);const _t=(0,o.withSelect)((e=>{const t=e("eventin/global");return{speakerList:t.getSpeakerList(),isLoading:t.isResolving("getSpeakerList")}})),Et=(0,o.withDispatch)((e=>{const t=e("eventin/global");return{refreshCategoryList:()=>t.invalidateResolution("getSpeakerCategories")}})),ht=(0,r.compose)(_t,Et)((e=>{const{modalOpen:t,setModalOpen:n,speakerList:l,refreshCategoryList:i,formType:r,...o}=e,[d,p]=(0,s.useState)(!1),[v]=G.A.useForm(),[_,E]=(0,s.useState)(!1),h="organizer"===r;return(0,a.createElement)(ge.A,{title:(0,m.__)(`Add New ${h?"Organizer":"Speaker"} Group`,"eventin"),open:t,onCancel:()=>n(!1),cancelText:(0,m.__)("Cancel","eventin"),okText:(0,m.__)(`Add ${h?"Organizer":"Speaker"} Group`,"eventin"),onOk:async()=>{E(!0);try{await v.validateFields();const e=v.getFieldsValue(!0),t=await T.A.speakerCategories.createCategory(e);if(!t?.id)throw new Error("Couldn't create speaker properly!");i(),n(!1)}catch(e){(0,c.doAction)("eventin_notification",{type:"error",message:(0,m.__)(`Couldn't Create ${h?"Organizer":"Speaker"} Group`,"eventin"),description:`Reason: ${e?.response?.message}`}),console.error(e)}finally{E(!1)}},confirmLoading:_,destroyOnClose:!0,styles:{body:{height:"50vh",overflowY:"auto",overflowX:"hidden",scrollbarWidth:"thin"}}},(0,a.createElement)(G.A,{layout:"vertical",form:v,scrollToFirstError:!0,preserve:!1},(0,a.createElement)(g.A,{gutter:[10,0],style:{paddingRight:"4px"}},(0,a.createElement)(u.A,{xs:24},(0,a.createElement)(G.A.Item,{name:"name",label:(0,m.__)((h?"Organizer":"Speaker")+" group name","eventin"),rules:[{required:!0,message:(h?"Organizer":"Speaker")+" name is required!"}]},(0,a.createElement)(Z.ks,{placeholder:(0,m.__)(`Name of ${h?"organizer":"speaker"} group`,"eventin"),size:"middle"}))),(0,a.createElement)(u.A,{md:24,xs:24},(0,a.createElement)(G.A.Item,{label:(0,m.__)("Single "+(h?"Organizer":"Speaker"),"eventin"),name:"speaker",tooltip:(0,m.__)(`Select the ${h?"organizer":"speaker"} for this event.`,"eventin")},(0,a.createElement)(_e.A,{placeholder:(0,m.__)(`Select the ${h?"organizer":"speaker"} for this event`,"eventin"),options:l},(0,a.createElement)(A.yl,{onClick:()=>p(!0)},(0,m.__)("Add new "+(h?"Organizer":"Speaker"),"eventin"))))),(0,a.createElement)(vt.A,{modalOpen:d,setModalOpen:p,formType:h?"organizer":"speaker"}))))})),{Title:ft,Text:xt}=pt.A,At=(0,o.withSelect)((e=>{const t=e("eventin/global");return{speakerCategories:t.getSpeakerCategories(),isLoading:t.isResolving("getSpeakerCategories")}})),yt=(0,r.compose)(At)((e=>{const{loading:t,speakers:n,speakerCategories:l}=e,{form:i}=(0,S.useEventSelectContext)(),r=G.A.useWatch("organizer_type",{form:i,preserve:!0}),o=e=>{i.setFieldsValue({organizer_type:e})},[c,d]=(0,s.useState)(!1),[p,g]=(0,s.useState)(!1),u=window?.location?.href?.includes(window?.localized_data_obj?.admin_url),_=[u&&{key:"group",children:(0,a.createElement)(G.A.Item,{name:"organizer_group",label:(0,m.__)("Group Organizers","eventin"),tooltip:(0,m.__)("Select the category which will be used as organizer.","eventin")},(0,a.createElement)(me.A,{placeholder:(0,m.__)("Select the category which will be used as organizer.","eventin"),options:l,fieldNames:{value:"id",label:"name"},mode:"multiple"},(0,a.createElement)(A.yl,{onClick:()=>g(!0)},(0,m.__)("Add new organizer","eventin"))))},{key:"single",children:(0,a.createElement)(G.A.Item,{label:u?(0,m.__)("Single Organizer","eventin"):"",tooltip:(0,m.__)("Select the single organizer.","eventin"),name:"organizer"},(0,a.createElement)(_e.A,{placeholder:(0,m.__)("Select the organizers for this event","eventin"),options:n},(0,a.createElement)(A.yl,{onClick:()=>d(!0)},(0,m.__)("Add new organizer","eventin"))))}].filter(Boolean),E=()=>u?(0,a.createElement)(v.A,{wrap:"wrap",gap:15,style:{marginBottom:"20px"}},(0,a.createElement)(A.Ay,{variant:"group"===r?A.zB:A.Vt,size:"default",sx:{height:"36px",padding:"5px 15px"},onClick:()=>o("group")},(0,m.__)("Group Organizers","eventin")),(0,a.createElement)(A.Ay,{variant:"single"===r?A.zB:A.Vt,size:"default",sx:{height:"36px",padding:"5px 15px"},onClick:()=>o("single")},(0,m.__)("Single Organizer","eventin"))):null;return(0,s.useEffect)((()=>{r||(u?i.setFieldsValue({organizer_type:"group"}):i.setFieldsValue({organizer_type:"single"}))}),[r]),(0,a.createElement)(a.Fragment,null,(0,a.createElement)(ee,null,(0,a.createElement)(ft,{level:4,className:"etn-section-subtitle"},(0,a.createElement)(x.ArrangeIcon,null)," ",(0,m.__)("Event Organizer ","eventin")),(0,a.createElement)(a.Fragment,null,t&&(0,a.createElement)(pe.A,{style:{padding:"50px 0"}},(0,a.createElement)(gt.A,null)),!t&&(0,a.createElement)(ut.A,{activeKey:u?r:"single",items:_,renderTabBar:()=>(0,a.createElement)(E,null)}))),(0,a.createElement)(ht,{modalOpen:p,setModalOpen:g,formType:"organizer"}),(0,a.createElement)(vt.A,{modalOpen:c,setModalOpen:d,formType:"organizer"}))})),bt=(0,o.withSelect)((e=>{const t=e("eventin/global");return{speakerCategories:t.getSpeakerCategories(),isLoading:t.isResolving("getSpeakerCategories")}})),kt=(0,r.compose)(bt)((e=>{const{loading:t,speakers:n,speakerCategories:l}=e,{form:i}=(0,S.useEventSelectContext)(),r=window?.location?.href?.includes(window?.localized_data_obj?.admin_url),o=G.A.useWatch("speaker_type",{form:i,preserve:!0}),c=e=>{i.setFieldsValue({speaker_type:e})},[d,p]=(0,s.useState)(!1),[g,u]=(0,s.useState)(!1),_=[r&&{key:"group",children:(0,a.createElement)(G.A.Item,{name:"speaker_group",label:(0,m.__)("Group Speakers","eventin"),tooltip:(0,m.__)("Select the categories which will be used as speakers.","eventin")},(0,a.createElement)(me.A,{placeholder:(0,m.__)("Select the categories which will be used as speakers","eventin"),options:l,fieldNames:{value:"id",label:"name"},mode:"multiple"},(0,a.createElement)(A.yl,{onClick:()=>u(!0)},(0,m.__)("Add new group","eventin"))))},{key:"single",children:(0,a.createElement)(G.A.Item,{label:r?(0,m.__)("Single Speaker","eventin"):"",name:"speaker",tooltip:(0,m.__)("Select the speakers for this event.","eventin")},(0,a.createElement)(_e.A,{placeholder:(0,m.__)("Select the speakers for this event","eventin"),options:n},(0,a.createElement)(A.yl,{onClick:()=>p(!0)},(0,m.__)("Add new speakers","eventin"))))}].filter(Boolean),E=()=>r?(0,a.createElement)(v.A,{wrap:"wrap",gap:15,style:{marginBottom:"20px"}},(0,a.createElement)(A.Ay,{variant:"group"===o?A.zB:A.Vt,size:"default",sx:{height:"36px",padding:"5px 15px"},onClick:()=>c("group")},(0,m.__)("Group Speakers","eventin")),(0,a.createElement)(A.Ay,{variant:"single"===o?A.zB:A.Vt,size:"default",sx:{height:"36px",padding:"5px 15px"},onClick:()=>c("single")},(0,m.__)("Single Speaker","eventin"))):null;return(0,s.useEffect)((()=>{o||(r?i.setFieldsValue({speaker_type:"group"}):i.setFieldsValue({speaker_type:"single"}))}),[o]),(0,a.createElement)(a.Fragment,null,(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header"},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("Speakers and Organizers","eventin")),(0,a.createElement)(X.A,{className:"etn-section-description"},(0,m.__)("Personalize speaker profiles to organize the perfect lineup for your event.","eventin")),(0,a.createElement)(Q.A,{href:w.DOCUMENTATION_LINK,target:"_blank"},(0,m.__)(" Please check our documentation for more details.","eventin")))),(0,a.createElement)(k.A,{level:4,className:"etn-section-subtitle"},(0,a.createElement)(x.MicIcon,null)," ",(0,m.__)("Event Speakers","eventin")),(0,a.createElement)("div",{style:{margin:"50px 0px"}},t&&(0,a.createElement)(pe.A,{style:{padding:"50px 0px"}},(0,a.createElement)(gt.A,null)),!t&&(0,a.createElement)(ut.A,{activeKey:r?o:"single",items:_,renderTabBar:()=>(0,a.createElement)(E,null)}))),(0,a.createElement)(ht,{modalOpen:g,setModalOpen:u}),(0,a.createElement)(vt.A,{modalOpen:d,setModalOpen:p}))}));var wt=n(45577);const Ct=(0,o.withSelect)((e=>{const t=e("eventin/global");return{speakerList:t.getSpeakerList(),isLoading:t.isResolving("getSpeakerList")}})),St=(0,r.compose)(Ct)((e=>{const{isLoading:t,speakerList:n}=e,l=(0,wt.A)(n);return(0,a.createElement)("div",null,(0,a.createElement)(kt,{loading:t,speakers:l}),(0,a.createElement)(yt,{loading:t,speakers:l}))}));var Tt=n(87667),Nt=n(32122),zt=n(41407),Lt=n(7746),It=n(50331),Ot=n(92051),Ft=n(14928),Dt=n(8228);const Rt=(0,Dt.A)("/images/landing.webp"),Pt=[{id:"event-one",title:(0,m.__)("Event Template One","eventin"),thumbnail:(0,Dt.A)("/images/event_template_1.webp"),is_pro:!1,externalLink:"https://product.themewinter.com/eventin/event/event-details-1/"},{id:"event-two",title:(0,m.__)("Event Template Two","eventin"),thumbnail:(0,Dt.A)("/images/event_template_2.webp"),is_pro:!0,externalLink:"https://product.themewinter.com/eventin/event/event-details-2/"},{id:"event-three",title:(0,m.__)("Event Template Three","eventin"),thumbnail:(0,Dt.A)("/images/event_template_3.webp"),is_pro:!0,externalLink:"https://product.themewinter.com/eventin/event/event-details-3/"}],Vt=(0,o.withSelect)((e=>{const t=e("eventin/global");return{settings:t.getSettings(),templateList:t.getTemplateList(),isLoading:t.isResolving("getTemplateList")}})),Mt=(0,r.compose)([Vt])((({settings:e,templateList:t,isLoading:n})=>{const[l,i]=(0,s.useState)(!1),r=Boolean(window.localized_data_obj.evnetin_pro_active),{form:o}=(0,S.useEventSelectContext)(),c=G.A.useWatch("event_layout",{form:o,preserve:!0}),d=t?.filter((e=>"event"===e?.type&&"publish"===e?.status))||[],{selectedTemplate:p,handleSelectionChange:v,clearSelection:_}=(0,Ft.A)(Pt,d,c,o,"event_layout");return(0,a.createElement)(ee,null,(0,a.createElement)("div",{style:{margin:"40px 0 0"}},(0,a.createElement)(Lt.A,{titleText:(0,m.__)("Event Landing Page","eventin"),labelText:(0,m.__)("Landing Page Template","eventin"),titleIcon:(0,a.createElement)(x.LayoutIcon,{height:22,width:22}),infoTooltipText:(0,m.__)("Select the event landing page template. Selecting a template will override the default event template settings.","eventin")}),(0,a.createElement)(g.A,{gutter:[20,20],style:{marginTop:"20px"}},(0,a.createElement)(u.A,{xs:24,md:12,lg:10},(0,a.createElement)(It.A,{buttonText:(0,m.__)("Select Template","eventin"),src:p?.thumbnail||Rt,handleClick:()=>i(!0),is_pro:!1,selectedTemplate:p,externalLink:p?.externalLink}))),(0,a.createElement)(Ot.A,{modalOpen:l,setModalOpen:i,modalTitle:(0,m.__)("Select Landing Page Template","eventin")},(0,a.createElement)(g.A,{gutter:[20,20],style:{marginTop:"20px"}},d.map((e=>(0,a.createElement)(u.A,{xs:24,md:12,lg:10,xxl:8,key:e?.id},(0,a.createElement)(Nt.A,{pro:e?.is_pro,src:e?.thumbnail||Rt,alt:"example landing",title:e?.name,id:e?.id,selected:String(c)===String(e?.id),handleClick:()=>v(e,r)})))),Pt.map((({id:e,title:t,thumbnail:n,is_pro:l,externalLink:i})=>(0,a.createElement)(u.A,{xs:24,md:12,lg:10,xxl:8,key:e},(0,a.createElement)(Tt.A.Ribbon,{text:(0,m.__)("Static","eventin")},(0,a.createElement)(Nt.A,{title:t,pro:l,src:n||Rt,alt:"example landing",id:e,selected:c===e,handleClick:()=>v({id:e,is_pro:l},r),externalLink:i})))))))))}));var Bt=n(5556),Wt=n.n(Bt),jt=n(36492);const{Link:qt}=pt.A,Ut=!!window.localized_data_obj.evnetin_pro_active,$t=localized_data_obj.site_url+"/wp-admin/post-new.php?post_type=page",Kt=(0,o.withSelect)((e=>{const t=e("eventin/global");return Ut?{certificateTemplates:t.getCertificateTemplates(),isLoading:t.isResolving("getCertificateTemplates")}:{certificateTemplates:[],isLoading:!1}})),Gt=(0,r.compose)([Kt])((e=>{const{certificateTemplates:t,isLoading:n}=e,l=t&&t?.map((e=>({value:e.id.toString(),label:e.title})));return(0,a.createElement)(g.A,{gutter:[16,16]},(0,a.createElement)(u.A,{xs:24},(0,a.createElement)(G.A.Item,{name:"certificate_template",style:{width:"100%"}},Ut?n?(0,a.createElement)(_.A,{paragraph:{rows:0,width:"100%"},active:!0,size:"large"}):(0,a.createElement)(jt.A,{showSearch:!0,style:{width:"100%"},size:"large",placeholder:(0,m.__)("Select Certificate Template","eventin"),optionFilterProp:"children",filterOption:(e,t)=>{var n;return(null!==(n=t?.label)&&void 0!==n?n:"").includes(e)},filterSort:(e,t)=>{var n,a;return(null!==(n=e?.label)&&void 0!==n?n:"").toLowerCase().localeCompare((null!==(a=t?.label)&&void 0!==a?a:"").toLowerCase())},options:l}):(0,a.createElement)(J.A,null)),(0,a.createElement)(v.A,null,(0,a.createElement)(qt,{href:$t,target:"_blank",style:{fontWeight:500,fontSize:"14px",lineHeight:"20px",color:"#1890FF",textDecoration:"underline"}},(0,m.__)("Create Certificate Templates","eventin")))))})),Yt=(0,Dt.A)("/images/event_certificate.webp"),Ht=({isUseLegacyTemplate:e,onToggle:t})=>(0,a.createElement)("div",{style:{margin:"10px 0px"}},(0,a.createElement)(A.Ay,{variant:A.jK,onClick:t},e?(0,m.__)("Use New Template","eventin"):(0,m.__)("Use Legacy Template","eventin")));Ht.propTypes={isUseLegacyTemplate:Wt().bool.isRequired,onToggle:Wt().func.isRequired};const Qt=(0,o.withSelect)((e=>{const t=e("eventin/global");return{settings:t.getSettings(),templateList:t.getTemplateList(),isLoadingTemplate:t.isResolving("getTemplateList")}})),Jt=(0,r.compose)([Qt])((({templateList:e,isLoadingTemplate:t})=>{const[n,l]=(0,a.useState)({isModalOpen:!1,selectedTemplate:null,isUseLegacyTemplate:!1}),{form:i}=(0,S.useEventSelectContext)(),r=G.A.useWatch("certificate_template",{form:i,preserve:!0}),o=G.A.useWatch("enable_legacy_certificate_template",{form:i,preserve:!0}),s=(0,a.useMemo)((()=>e?.filter((e=>"certificate"===e?.type&&"publish"===e?.status))||[]),[e]),c=(0,a.useMemo)((()=>Number(r)),[r]),d=(0,a.useCallback)((()=>{l((e=>{const t=!e.isUseLegacyTemplate;return i.setFieldsValue({enable_legacy_certificate_template:t}),{...e,isUseLegacyTemplate:t}}))}),[i]),p=(0,a.useCallback)((e=>{const{id:t,isPro:n}=e||{};t===r?i.setFieldsValue({certificate_template:""}):(l((t=>({...t,selectedTemplate:e}))),(!n||n&&window.localized_data_obj.evnetin_pro_active)&&i.setFieldsValue({certificate_template:t}))}),[r,i]);(0,a.useEffect)((()=>{l((e=>({...e,isUseLegacyTemplate:o||e.isUseLegacyTemplate})))}),[o]),(0,a.useEffect)((()=>{const e=s.find((e=>e.id===c));l((t=>({...t,selectedTemplate:e||null})))}),[c,s]);const v=s.length>0;return(0,a.createElement)(ee,null,(0,a.createElement)("div",{style:{margin:"20px 0 0"}},(0,a.createElement)(Lt.A,{titleText:(0,m.__)("Event Certificate","eventin"),labelText:(0,m.__)("Event Certificate","eventin"),titleIcon:(0,a.createElement)(x.DiplomaIcon,{height:22,width:22}),infoTooltipText:(0,m.__)("Select the template of certificate for this event","eventin")}),(0,a.createElement)(Ht,{isUseLegacyTemplate:n.isUseLegacyTemplate,onToggle:d}),(0,a.createElement)(g.A,{gutter:[20,20]},n.isUseLegacyTemplate?(0,a.createElement)(u.A,{xs:24,md:12,lg:10},(0,a.createElement)(Gt,null)):(0,a.createElement)(u.A,{xs:24,md:12,lg:10},(0,a.createElement)(It.A,{buttonText:(0,m.__)("Select Template","eventin"),src:n.selectedTemplate?.thumbnail||Yt,handleClick:()=>l((e=>({...e,isModalOpen:!0}))),selectedTemplate:n.selectedTemplate,isPro:!0}))),(0,a.createElement)(Ot.A,{modalOpen:n.isModalOpen,setModalOpen:e=>l((t=>({...t,isModalOpen:e}))),modalTitle:(0,m.__)("Select Certificate Template","eventin"),isTemplateAvailable:v},(0,a.createElement)(g.A,{gutter:[20,20],style:{marginTop:"20px"}},(0,a.createElement)(u.A,{xs:24,md:12,lg:10,xxl:8,style:{minWidth:"180px"}},(0,a.createElement)(zt.A,{title:(0,m.__)("None","eventin"),onClick:()=>{l((e=>({...e,selectedTemplate:null}))),i.setFieldsValue({certificate_template:""})}})),s.map((e=>(0,a.createElement)(u.A,{xs:24,md:12,lg:10,xxl:8,key:e.id},(0,a.createElement)(Nt.A,{pro:e.is_pro,src:e.thumbnail||Yt,alt:"example certificate",title:e.name,id:e.id,selected:c===e.id,handleClick:()=>p(e)}))))))))})),Xt=(0,Dt.A)("/images/event_ticket.webp"),Zt=[{id:"style-2",title:(0,m.__)("Template Two","eventin"),thumbnail:(0,Dt.A)("/images/ticket_template_2.webp"),is_pro:!0},{id:"style-1",title:(0,m.__)("Template One","eventin"),thumbnail:(0,Dt.A)("/images/ticket_template_1.webp"),is_pro:!1}],en=(0,o.withSelect)((e=>{const t=e("eventin/global");return{settings:t.getSettings(),templateList:t.getTemplateList(),isLoadingTemplate:t.isResolving("getTemplateList")}})),tn=(0,r.compose)([en])((({settings:e,templateList:t,isLoadingTemplate:n})=>{const[l,i]=(0,s.useState)(!1),r=Boolean(window.localized_data_obj.evnetin_pro_active),{form:o}=(0,S.useEventSelectContext)(),c=G.A.useWatch("ticket_template",{form:o,preserve:!0}),d=t?.filter((e=>"ticket"===e?.type&&"publish"===e?.status))||[],{selectedTemplate:p,handleSelectionChange:v,clearSelection:_}=(0,Ft.A)(Zt,d,c,o,"ticket_template");return(0,a.createElement)(ee,null,(0,a.createElement)("div",{style:{margin:"20px 0 0"}},(0,a.createElement)(Lt.A,{titleText:(0,m.__)("Event Tickets","eventin"),labelText:(0,m.__)("Event Tickets","eventin"),titleIcon:(0,a.createElement)(x.TicketIcon,{height:22,width:22}),infoTooltipText:(0,m.__)("Select the template of ticket for this event","eventin")}),(0,a.createElement)(g.A,{gutter:[20,20],style:{marginTop:"20px"}},(0,a.createElement)(u.A,{xs:24,md:12,lg:10},(0,a.createElement)(It.A,{buttonText:(0,m.__)("Select Template","eventin"),src:p?.thumbnail||Xt,handleClick:()=>i(!0),is_pro:!1,selectedTemplate:p}))),(0,a.createElement)(Ot.A,{modalOpen:l,setModalOpen:i,modalTitle:(0,m.__)("Select Ticket Template","eventin")},(0,a.createElement)(g.A,{gutter:[20,20],style:{marginTop:"20px"}},d.map((e=>(0,a.createElement)(u.A,{xs:24,md:12,lg:10,xxl:8,key:e?.id},(0,a.createElement)(Nt.A,{pro:e?.is_pro,src:e?.thumbnail||Xt,alt:"example ticket",title:e?.name,id:e?.id,selected:String(e?.id)===String(c),handleClick:()=>v(e,r)})))),Zt.map((({id:e,title:t,thumbnail:n,is_pro:l})=>(0,a.createElement)(u.A,{xs:24,md:12,lg:10,xxl:8,key:e},(0,a.createElement)(Tt.A.Ribbon,{text:(0,m.__)("Static","eventin")},(0,a.createElement)(Nt.A,{title:t,pro:l,src:n||Xt,alt:"example ticket",id:e,selected:c===e,handleClick:()=>v({id:e,is_pro:l},r)})))))))))})),nn=(0,o.withDispatch)((e=>{const t=e("eventin/global");return{invalidateTemplateList:()=>t.invalidateResolution("getTemplateList")}})),an=(0,r.compose)(nn)((e=>{const{invalidateTemplateList:t}=e||{};return(0,s.useEffect)((()=>{t()}),[]),(0,a.createElement)(a.Fragment,null,(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header"},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("Template Setup","eventin")),(0,a.createElement)(X.A,{className:"etn-section-description"},(0,m.__)("Setup your event landing page, certificate layout and ticket layout.","eventin"))))),(0,a.createElement)(Mt,null),(0,a.createElement)(Jt,null),(0,a.createElement)(tn,null))}));var ln=n(866),rn=n(95376);const{useBreakpoint:on}=p.Ay,{RangePicker:sn}=de.A,cn=e=>{const{modalOpen:t,setModalOpen:n,ticketData:l}=e,[i]=G.A.useForm(),{form:r,id:o}=(0,S.useEventSelectContext)(),[c,d]=(0,s.useState)(!1),[p,v]=(0,s.useState)(!1),_=Object.keys(l)?.length>0;(0,s.useEffect)((()=>{window?.localized_data_obj?.evnetin_pro_active&&v(!0),t&&(_?i.setFieldsValue({...l,date_range:[f()(l?.start_date).isValid()?f()(l?.start_date):null,f()(l?.end_date).isValid()?f()(l?.end_date):null]}):i.resetFields())}),[t]);const E=on().sm;return(0,a.createElement)(ge.A,{title:(0,m.__)(_?"Edit Ticket":"New Ticket","eventin"),open:t,onCancel:()=>{n(!1)},cancelText:(0,m.__)("Cancel","eventin"),okText:_?(0,m.__)("Save Changes","eventin"):(0,m.__)("Add Ticket","eventin"),onOk:async()=>{d(!0);try{await i.validateFields();const e=i.getFieldsValue(),t=e.etn_ticket_price?e.etn_ticket_price:0;e.etn_ticket_price=t;const a=f()(e.date_range[0]).format("YYYY-MM-DD"),s=f()(e.date_range[1]).format("YYYY-MM-DD");let c=r.getFieldValue("ticket_variations");if(_){const t=l.etn_ticket_slug,n=c.findIndex((e=>e.etn_ticket_slug===t));c[n]={...c[n],...e,start_date:a,end_date:s},r.setFieldsValue({ticket_variations:c})}else{const t=Math.floor(1e4*Math.random()),n=e.etn_ticket_name?.split(" ")?.join("-")?.toLowerCase();e.etn_ticket_slug=`ticket-${o}-${n}-${t}`,e.etn_sold_tickets=0,e.etn_enable_ticket=!0;const l={...e,start_date:a,end_date:s},i=[...c,l];r.setFieldsValue({ticket_variations:i})}n(!1),i.resetFields()}catch(e){console.error("Error creating new ticket:",e)}finally{d(!1)}},confirmLoading:c,centered:!0,style:E?{minWidth:"568px"}:{},styles:{body:{overFlowY:"auto"}}},(0,a.createElement)(G.A,{layout:"vertical",form:i,scrollToFirstError:!0},(0,a.createElement)(g.A,{gutter:[20,0]},(0,a.createElement)(u.A,{xs:24},(0,a.createElement)(G.A.Item,{name:"etn_ticket_name",label:(0,m.__)("Ticket Name","eventin"),rules:[{required:!0,message:(0,m.__)("Ticket Name is required!","eventin")}],style:{marginBottom:0}},(0,a.createElement)(Z.ks,{placeholder:(0,m.__)("Enter Ticket Name","eventin"),size:"large"}))),(0,a.createElement)(u.A,{xs:24,md:12},(0,a.createElement)(G.A.Item,{name:"etn_avaiilable_tickets",label:(0,m.__)("No. of Tickets","eventin")},(0,a.createElement)(ot.A,{placeholder:"00"}))),(0,a.createElement)(u.A,{xs:24,md:12},(0,a.createElement)(G.A.Item,{name:"etn_ticket_price",label:(0,m.__)("Ticket Price","eventin")},(0,a.createElement)(ot.A,{placeholder:"00",formatter:e=>`${e}`.replace(/\B(?=(\d{3})+(?!\d))/g,",")}))),(0,a.createElement)(u.A,{xs:24,md:24},(0,a.createElement)(Ce.A,{dateRange:"date_range",startDate:"start_date",startTime:"start_time",endDate:"end_date",endTime:"end_time",form:i})),(0,a.createElement)(u.A,{xs:24,md:12},(0,a.createElement)(G.A.Item,{name:"etn_min_ticket",label:(0,m.__)("Minimum Quantity","eventin")},p?(0,a.createElement)(ot.A,{placeholder:"00"}):(0,a.createElement)(rn.A,null))),(0,a.createElement)(u.A,{xs:24,md:12},(0,a.createElement)(G.A.Item,{name:"etn_max_ticket",label:(0,m.__)("Maximum Quantity","eventin")},p?(0,a.createElement)(ot.A,{placeholder:"00"}):(0,a.createElement)(rn.A,null))))))},mn=(0,o.withSelect)((e=>({settings:e("eventin/global").getSettings()}))),dn=(0,r.compose)(mn)((function(e){const{settings:t}=e,{form:n}=(0,S.useEventSelectContext)(),l=G.A.useWatch("ticket_variations",{form:n,preserve:!0})||[],[i,r]=(0,s.useState)(!1),[o,c]=(0,s.useState)({}),d=l&&l.some((e=>e.etn_ticket_price>0)),p=Boolean(t?.sell_tickets||t?.etn_sells_engine_stripe||t?.paypal_status),_=d&&!p,{hasDuplicate:E,duplicateName:h}=(0,z.checkDuplicateTicketName)(l);return(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header"},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("Tickets and Pricing","eventin")),(0,a.createElement)(X.A,{className:"etn-section-description"},(0,m.__)("Set up your ticketing system with ease and flexibility.","eventin")))),_&&(0,a.createElement)(Ke.A,{title:(0,m.__)("Currently, no payment method is enabled","eventin"),description:(0,m.__)("Please enable a payment method on the Settings --\x3e Payment Settings tab.","eventin"),buttonText:(0,m.__)("Go to Settings","eventin"),redirectUrl:`${window.localized_data_obj.site_url}/wp-admin/admin.php?page=eventin#/settings/payments`}),E&&(0,a.createElement)(ae.A,{type:"warning",showIcon:!0,message:(0,m.__)(`You've created "${h}" ticket more than once!`,"eventin"),description:(0,m.__)("Creating tickets with the same name will cause unexpected behavior of the application.","eventin"),style:{margin:"10px 0px"}}),(0,a.createElement)(a.Fragment,null,(0,a.createElement)(g.A,{gutter:[16,16],style:{margin:"50px 0px"}},(0,a.createElement)(u.A,{xs:24,md:24,xxl:24},(0,a.createElement)(v.A,{gap:3},(0,a.createElement)(X.A,{style:{fontWeight:600}},(0,m.__)("Tickets","eventin")),(0,a.createElement)(j.A,{title:(0,m.__)("Create tickets for your event. You can create multiple tickets with different prices and quantities.","eventin")},(0,a.createElement)("span",null,(0,a.createElement)(x.InfoCircleOutlined,{width:15,height:15}))))),l?.map((e=>(0,a.createElement)(u.A,{xs:24,md:12,key:e.etn_ticket_slug},(0,a.createElement)(ln.R,{currency:window?.localized_data_obj?.currency_symbol,variation:e,setFormModalOpen:r,setTicketData:c,settingsData:t})))),(0,a.createElement)(u.A,{xs:24,md:12},(0,a.createElement)(ln._,{onClick:()=>{c({}),r(!0)}}))),(0,a.createElement)(cn,{modalOpen:i,setModalOpen:r,ticketData:o})))}));var pn=n(58950);function gn(e){const{form:t,sourceData:n}=(0,S.useEventSelectContext)(),i=localized_data_obj.site_url,r=!!window.localized_data_obj?.timetics_pro_active,o=t.getFieldsValue(!0),s=o?.id,c=i+`/wp-admin/admin.php?page=timetics#/seats/${s}/create?eventin=true`,d=o?.ticket_variations?.length>0,p=o?.enable_seatmap,g=(0,l.useNavigate)(),u=(G.A.useWatch("enable_seatmap",{form:t,preserve:!0}),!d||!Boolean(n?.enable_seatmap)),_=[{key:"1",label:(0,a.createElement)(Y.A,{direction:"vertical",size:0},(0,a.createElement)(X.A,{sx:{color:"#334155",fontWeight:500}},(0,m.__)("Active Seat Map","eventin")),(0,a.createElement)(X.A,{sx:{fontSize:"14px",color:"#747474"}},(0,m.__)("Remember to save the event after activating the seat map before going to the canvas.","eventin"))),children:(0,a.createElement)(v.A,{gap:10,justify:"space-between",align:"center",wrap:"wrap",style:{border:"1px solid #F0F0F0",borderRadius:"6px",padding:"20px",marginTop:"30px",backgroundColor:"#fff"}},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{sx:{fontSize:16,fontWeight:600,marginTop:0}},(0,m.__)("Visual Seat Map","eventin")),(0,a.createElement)(X.A,null,(0,m.__)("Add visual seat map for your event","eventin"))),r?(0,a.createElement)(j.A,{title:u?(0,m.__)("Before going to canvas you need to save the event","eventin"):""},(0,a.createElement)(A.Ay,{variant:A.Vt,size:"middle",disabled:u,onClick:()=>window.open(c,"_blank"),sx:{color:"#2b2b2b",borderColor:"#d9d9d9"}},(0,m.__)("Go to canvas","eventin"))):(0,a.createElement)(U.u,{type:pn.fG.type,size:pn.fG.size,variantstyle:pn.fG.style,onClick:()=>window.open("https://arraytics.com/timetics/","_blank")},(0,m.__)("Upgrade Timeics Pro","eventin"),(0,a.createElement)(x.ProIcon,null))),showArrow:!1,extra:(0,a.createElement)(G.A.Item,{name:"enable_seatmap",valuePropName:"checked"},(0,a.createElement)(H.A,null))}];return(0,a.createElement)(a.Fragment,null,!d&&(0,a.createElement)(ae.A,{style:{margin:"30px 0"},message:(0,m.__)("Currently, no ticket variations have been created for this event.","eventin"),action:(0,a.createElement)(A.Ay,{variant:A.Vt,size:"middle",sx:{color:"#2b2b2b"},onClick:()=>g(`/events/create/${s}/tickets`)},(0,m.__)("Create Ticket","eventin")),type:"warning",showIcon:!0}),(0,a.createElement)(K.A,{activeKey:p&&["1"],items:_,onChange:e=>{t.setFieldValue("enable_seatmap",Boolean(e.length))},className:"eventin-collapse-wrapper",style:{marginTop:"30px"}}))}const un=(0,o.withSelect)((e=>{const t=e("eventin/global");return{settings:t.getSettings(),isLoading:t.isResolving("getSettings")}})),vn=(0,r.compose)(un)((function(e){const{settings:t,isLoading:n}=e,l="on"===t?.modules?.seat_map;return n?(0,a.createElement)(_.A,{active:!0}):(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header"},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("Visual Seat Map","eventin")),(0,a.createElement)(X.A,{className:"etn-section-description"},(0,m.__)("Set up your ticketing system with visual seat map.","eventin")))),l?(0,a.createElement)(gn,null):(0,a.createElement)(Ke.A,{title:(0,m.__)("Enable Visual Seat Map","eventin"),description:(0,m.__)('Please enable "Seat Map" on the event addons page to create an event visual seat map.',"eventin"),buttonText:(0,m.__)("Go to Addons","eventin"),redirectUrl:`${window.localized_data_obj.site_url}/wp-admin/admin.php?page=eventin#/add-ons`}))})),En=window.location.href.includes("eventin#/"),hn=[{label:(0,m.__)("Basic Info","eventin"),title:(0,m.__)("Basic Information","eventin"),slug:"basic",icon:x.BasicInfoIcon,page:$e},{label:(0,m.__)("Speakers & Organizers","eventin"),title:(0,m.__)("Speakers & Organizers","eventin"),slug:"speaker-organizer",icon:x.SpeakerAndOrganizerIcon,page:St},{label:(0,m.__)("Schedule","eventin"),title:(0,m.__)("Schedule","eventin"),slug:"schedule",icon:x.ScheduleIcon,page:be},{label:(0,m.__)("Tickets & Pricing","eventin"),title:(0,m.__)("Tickets & Pricing","eventin"),slug:"tickets",icon:x.TicketIcon,page:dn},En&&{label:(0,m.__)("Visual Seat Map","eventin"),title:(0,m.__)("Visual Seat Map","eventin"),slug:"seat-map",icon:x.SeatIcon,page:vn}].filter(Boolean),fn=[{label:(0,m.__)("Branding","eventin"),title:(0,m.__)("Branding","eventin"),slug:"branding",icon:x.LandingPageIcon,page:function(){return(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header"},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("Branding","eventin")),(0,a.createElement)(X.A,{className:"etn-section-description"},(0,m.__)("Create a stunning Branding that serves as the visual centerpiece of your event.","eventin")))),(0,a.createElement)("div",{style:{margin:"40px 0px"}},(0,a.createElement)(rt,null),(0,a.createElement)(nt,null),(0,a.createElement)(lt,null)))}},{label:(0,m.__)("Template Setup","eventin"),title:(0,m.__)("Template Setup","eventin"),slug:"template-setup",icon:x.TemplateSetupIcon,page:an},{label:(0,m.__)("FAQ","eventin"),title:(0,m.__)("FAQ","eventin"),slug:"faq",icon:x.FaqIcon,page:function(e){const t=!!window.localized_data_obj.evnetin_pro_active,[n,l]=(0,s.useState)(0);return(0,a.createElement)(ee,null,(0,a.createElement)("div",{className:"etn-page-header"},(0,a.createElement)("div",null,(0,a.createElement)(k.A,{level:3,className:"etn-section-title"},(0,m.__)("FAQ","eventin")),(0,a.createElement)(X.A,{className:"etn-section-description"},(0,m.__)("Create a detailed FAQ section to answer attendee inquiries and clear any confusion.","eventin")))),(0,a.createElement)("div",{style:{margin:"50px 0px"}},t?(0,a.createElement)(G.A.List,{name:"faq"},((e,{add:t,remove:i},{errors:r})=>(0,a.createElement)("div",null,e.map((e=>(0,a.createElement)(Je,{key:e.key,field:e,remove:i,expandedKey:n,setExpandedKey:l}))),(0,a.createElement)(G.A.Item,null,(0,a.createElement)(A.Ay,{variant:A.Rm,onClick:()=>t(),icon:(0,a.createElement)(x.PlusOutlined,{width:16,height:16}),size:"middle",sx:{width:"100%",fontSize:"14px",marginTop:"10px"}},(0,m.__)("Add FAQ","eventin")))))):(0,a.createElement)(Ye.A,null)))}},{label:(0,m.__)("Extra Fields","eventin"),title:(0,m.__)("Extra Fields","eventin"),slug:"extra-fields",icon:x.RegistrationIcon,page:Qe}],xn=[{label:(0,m.__)("Integrations","eventin"),title:(0,m.__)("Integrations","eventin"),slug:"integrations",icon:x.IntegrationsIcon,page:et},{label:(0,m.__)("RSVP","eventin"),title:(0,m.__)("RSVP","eventin"),slug:"rsvp",icon:x.RSVPIcon,page:dt},{label:(0,m.__)("Additional Page","eventin"),title:(0,m.__)("Additional Page","eventin"),slug:"additional-page",icon:x.ExtraPageIcon,page:ce}],An=[{label:(0,m.__)("Setup","eventin"),children:hn},{label:(0,m.__)("Customize","eventin"),children:fn},{label:(0,m.__)("Settings","eventin"),children:xn}],{useBreakpoint:yn}=p.Ay,bn=function({child:e,index:t,showLabel:n}){if(n)return(0,a.createElement)("li",{key:t+e.slug},(0,a.createElement)(q.NavLink,{replace:!0,to:e.slug},(({isActive:t})=>(0,a.createElement)(A.Ay,{className:"eventin-event-details-sidebar-button",variant:A.pz,icon:(0,a.createElement)(e.icon,null),sx:t?U.K:{}},e.label))));const l={width:"45px !important"};return(0,a.createElement)("li",{key:t+e.slug},(0,a.createElement)(q.NavLink,{replace:!0,to:e.slug},(({isActive:t})=>(0,a.createElement)(j.A,{title:e.label},(0,a.createElement)(A.Ay,{variant:A.pz,icon:(0,a.createElement)(e.icon,null),sx:t?{...U.K,...l}:l,size:"small"})))))},kn=(0,o.withSelect)((e=>{const t=e("eventin/global");return{settings:t.getSettings(),isSettingsLoading:t.isResolving("getSettings")}})),wn=(0,r.compose)(kn)((function(e){const{settings:t,...n}=e;let l=An;var i;i=An,l="on"===t?.modules?.rsvp?i:i?.map((e=>({...e,children:e.children?.filter((e=>"rsvp"!==e.slug))})));const r=yn().md;return(0,a.createElement)(O,{...n,style:{minWidth:!r&&"85px"}},An?.map(((e,t)=>(0,a.createElement)($.A,{key:t},(0,a.createElement)(F,null,e.label),(0,a.createElement)("ul",{className:"eventin-sidebar-list"},e.children?.map(((e,t)=>(0,a.createElement)(bn,{key:t+e.slug,child:e,index:t,showLabel:r}))))))))})),Cn=(0,o.withDispatch)((e=>({setShouldRevalidateEventList:e("eventin/global").setRevalidateEventList}))),Sn=(0,r.compose)(Cn)((function(e){const{setShouldRevalidateEventList:t}=e,{form:n,isLoading:i,id:r}=(0,S.useEventSelectContext)(),{setSaveButtonLoading:o,setSourceData:s}=(0,S.useEventDispatchContext)();return i?(0,a.createElement)(_.A,{active:!0}):(0,a.createElement)(G.A.Provider,{onFormFinish:e=>{"event-create-form"===e&&(async()=>{o(!0);try{await n.validateFields();const e=L(n.getFieldsValue(!0)),a=e.status?.toLowerCase();["draft","private","publish"].includes(a)||(e.status="publish");const l=await T.A.events.updateEvent(r,e);l?.id&&s(l),(0,c.doAction)("eventin_notification",{type:"success",message:(0,m.__)("Event updated successfully","eventin")}),t(!0)}catch(e){(0,c.doAction)("eventin_notification",{type:"error",message:(0,m.__)("Couldn't Update Event!","eventin"),description:`Reason: ${e?.message}`})}finally{setTimeout((()=>{o(!1)}),500)}})()}},(0,a.createElement)(G.A,{form:n,name:"event-create-form",layout:"vertical",autoComplete:"on",scrollToFirstError:!0,onFinishFailed:e=>{var t;e.errorFields.length>0&&(t=e.errorFields[0].name[0],n.scrollToField(t,{behavior:"smooth",block:"start"}))}},(0,a.createElement)(l.Outlet,null)))}));function Tn(){return(0,a.createElement)(S.default,null,(0,a.createElement)(W,null),(0,a.createElement)(I,{className:"eventin-event-details-section"},(0,a.createElement)(wn,{className:"eventin-event-details-sidebar"}),(0,a.createElement)(D,{className:"eventin-event-details-main"},(0,a.createElement)(Sn,null))))}const Nn=[...hn,...fn,...xn];function zn(){return(0,a.createElement)(l.Routes,null,(0,a.createElement)(l.Route,{element:(0,a.createElement)(Tn,null)},Nn.map(((e,t)=>(0,a.createElement)(l.Route,{key:t,path:e.slug,element:(0,a.createElement)(e.page,{title:e.title})}))),(0,a.createElement)(l.Route,{path:"*",element:(0,a.createElement)(l.Navigate,{to:hn[0].slug})})))}}}]);