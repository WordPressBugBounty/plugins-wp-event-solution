"use strict";(globalThis.webpackChunkwp_event_solution=globalThis.webpackChunkwp_event_solution||[]).push([[977],{470933(e,t,n){n.d(t,{A:()=>a});const a={icon:{tag:"svg",attrs:{viewBox:"64 64 896 896",focusable:"false"},children:[{tag:"path",attrs:{d:"M880.1 154H143.9c-24.5 0-39.8 26.7-27.5 48L349 597.4V838c0 17.7 14.2 32 31.8 32h262.4c17.6 0 31.8-14.3 31.8-32V597.4L907.7 202c12.2-21.3-3.1-48-27.6-48zM603.4 798H420.6V642h182.9v156zm9.6-236.6l-9.5 16.6h-183l-9.5-16.6L212.7 226h598.6L613 561.4z"}}]},name:"filter",theme:"outlined"}},144290(e,t,n){n.d(t,{A:()=>s});var a=n(58168),i=n(951609),r=n(470933),o=n(812226),l=function(e,t){return i.createElement(o.A,(0,a.A)({},e,{ref:t,icon:r.A}))};const s=i.forwardRef(l)},862949(e,t,n){n.d(t,{A:()=>r});var a=n(951609),i=n(406836);const r=({height:e=16,width:t=16,strokeColor:n="#FAAD14",fillColor:r="#FFF5E2"})=>(0,i.iconCreator)(()=>(({height:e,width:t,strokeColor:n,fillColor:i})=>(0,a.createElement)("svg",{width:t,height:e,id:"Layer_1",xmlns:"http://www.w3.org/2000/svg",xmlnsXlink:"http://www.w3.org/1999/xlink",viewBox:"0 0 32 32",enableBackground:"new 0 0 32 32",xmlSpace:"preserve"},(0,a.createElement)("polyline",{fill:"none",stroke:n,strokeWidth:2,strokeMiterlimit:10,points:"3,17 16,4 29,17 "}),(0,a.createElement)("polyline",{fill:"none",stroke:"#000000",strokeWidth:2,strokeMiterlimit:10,points:"6,14 6,27 13,27 13,17 19,17 19,27 26,27  26,14 "})))({height:e,width:t,strokeColor:n,fillColor:r}))},740728(e,t,n){n.d(t,{A:()=>p});var a=n(951609),i=n(527723),r=n(650400),o=n(389500),l=n(236492),s=n(699150),c=n(972121),d=n(899489);const p=({total:e=0,currentPage:t=1,pageSize:n=10,onPageChange:p,onPageSizeChange:m,pageSizeOptions:u=["5","10","20","50","100"],wrapperClassName:g="eventin-pagination-wrapper"})=>{const v=0===e?0:(t-1)*n+1,f=Math.min(t*n,e),h=e=>{p&&p(e)};return(0,a.createElement)(d.C,{className:g},(0,a.createElement)("div",{className:"pagination-left"},(0,a.createElement)("span",{className:"rows-per-page-label"},(0,i.__)("Rows per page:","eventin")),(0,a.createElement)(l.A,{value:n.toString(),onChange:e=>{m&&m(e)},options:u.map(e=>({value:e,label:e})),size:"middle"})),(0,a.createElement)("div",{className:"pagination-right"},(0,a.createElement)("span",{className:"pagination-info"},v,"-",f," ",(0,i.__)("of","eventin")," ",e),(0,a.createElement)(o.A,{current:t,total:e,pageSize:n,onChange:h,showSizeChanger:!1,showQuickJumper:!1,showTotal:!1,prevIcon:(0,a.createElement)(r.Ay,{icon:(0,a.createElement)(s.A,null),iconPosition:"start",variant:"outlined",onClick:()=>h(t-1),disabled:1===t,style:{height:"100%"}},(0,i.__)("Previous","eventin")),nextIcon:(0,a.createElement)(r.Ay,{icon:(0,a.createElement)(c.A,null),iconPosition:"end",variant:"outlined",onClick:()=>h(t+1),disabled:t===e,style:{height:"100%"}},(0,i.__)("Next","eventin")),simple:!1})))}},899489(e,t,n){n.d(t,{C:()=>a});const a=n(769815).default.div`
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 16px;

	.pagination-left {
		display: flex;
		align-items: center;
		gap: 8px;
		color: #71717a;
		font-size: 14px;

		.rows-per-page-label {
			white-space: nowrap;
			font-weight: 400;
		}

		.ant-select {
			min-width: 70px;

			.ant-select-selector {
				border-color: #e4e4e7;
				border-radius: 6px;
			}
		}
	}

	.pagination-right {
		display: flex;
		align-items: center;
		gap: 24px;

		.pagination-info {
			color: #71717a;
			font-size: 14px;
			font-weight: 400;
		}

		.ant-pagination {
			display: flex;
			align-items: center;
			gap: 8px !important;
			margin: 0;

			li {
				margin-inline: 0px !important;
			}

			.ant-pagination-prev,
			.ant-pagination-next {
				min-width: auto;
				height: 36px;
				color: #4b4b4b;
				font-size: 14px;
				font-weight: 500;
				.ant-pagination-item-link {
					border: 1px solid #d4d4d8;
					border-radius: 4px;
					background-color: transparent;
					display: flex;
					align-items: center;
					justify-content: center;
					color: #71717a;
					font-size: 13px;
					padding: 0 12px;
					height: 36px;
					font-weight: 400;

					&:hover {
						border-color: #a1a1aa;
						color: #52525b;
						background-color: transparent;
					}
				}

				&.ant-pagination-disabled {
					.ant-pagination-item-link {
						border-color: #e4e4e7;
						color: #d4d4d8;
						background-color: transparent;
						cursor: not-allowed;

						&:hover {
							border-color: #e4e4e7;
							color: #d4d4d8;
							background-color: transparent;
						}
					}
				}
			}

			.ant-pagination-item {
				border: 1px solid #d9dde3;
				border-radius: 4px;
				min-width: 36px;
				height: 36px;
				display: flex;
				align-items: center;
				justify-content: center;
				font-size: 13px;
				background-color: white;
				line-height: 34px;

				a {
					color: #71717a;
					font-weight: 400;
				}

				&:hover {
					border-color: #f2e8ff;
					background-color: #f2e8ff;

					a {
						color: #52525b;
					}
				}

				&.ant-pagination-item-active {
					background-color: #f2e8ff;
					border-color: #f2e8ff;

					a {
						color: #18181b;
						font-weight: 500;
					}

					&:hover {
						background-color: #f2e8ff;
						border-color: #f2e8ff;

						a {
							color: #18181b;
						}
					}
				}
			}
		}
	}

	@media ( max-width: 768px ) {
		flex-direction: column;
		gap: 16px;
		align-items: flex-start;

		.pagination-right {
			width: 100%;
			flex-direction: column;
			align-items: flex-start;
			gap: 12px;
		}
	}
`},134388(e,t,n){n.d(t,{i:()=>l});var a=n(951609),i=n(527723),r=n(354725),o=n(848842);const l=e=>[{key:"json",label:(0,a.createElement)(o.A,{style:{padding:"4px 0",fontSize:"14px",marginLeft:"6px"}},(0,i.__)("Export JSON Format","eventin")),icon:(0,a.createElement)(r.JsonFileIcon,null),onClick:()=>e("json")},{key:"csv",label:(0,a.createElement)(o.A,{style:{padding:"4px 0",fontSize:"14px",marginLeft:"6px"}},(0,i.__)("Export CSV Format","eventin")),icon:(0,a.createElement)(r.CsvFileIcon,null),onClick:()=>e("csv")}]},664464(e,t,n){n.d(t,{A:()=>p});var a=n(951609),i=n(811721),r=n(832099),o=n(607638),l=n(354725),s=n(527723),c=n(250620),d=n(134388);const p=({type:e,arrayOfIds:t,shouldShow:n,eventId:p,isSelectingItems:m})=>{const{isDownloading:u,handleExport:g}=(0,c.i)({type:e,arrayOfIds:t,eventId:p}),v={display:"flex",alignItems:"center",borderColor:"#d9d9d9",fontSize:"14px",fontWeight:400,color:"#64748B",height:"36px",padding:"10px",borderTopRightRadius:m?"4px":"0px",borderBottomRightRadius:m?"4px":"0px"};return(0,a.createElement)(r.A,{title:n?(0,s.__)("Upgrade to Pro","eventin"):(0,s.__)("Download table data","eventin")},n?(0,a.createElement)(o.Ay,{variant:o.Vt,onClick:()=>window.open("https://themewinter.com/eventin/pricing/","_blank"),sx:v},(0,a.createElement)(l.ExportIcon,{width:16,height:16}),(0,a.createElement)(l.ProFlagIcon,null)):(0,a.createElement)(i.A,{menu:{items:(0,d.i)(g)},placement:"bottomRight",arrow:!0,disabled:n},(0,a.createElement)(o.Ay,{variant:o.Vt,loading:u,sx:v},(0,a.createElement)(l.ExportIcon,{width:16,height:16}))))}},960254(e,t,n){n.d(t,{R:()=>r});var a=n(601455),i=n.n(a);const r=async({type:e,format:t,ids:n=[],eventId:a})=>{let r=`/eventin/v2/${e}/export`;a&&(r+=`?event_id=${a}`);const o=await i()({path:r,method:"POST",data:{format:t,ids:n},parse:"csv"!==t});return"csv"===t?o.text():o}},250620(e,t,n){n.d(t,{i:()=>s});var a=n(986087),i=n(152619),r=n(527723),o=n(960254),l=n(696781);const s=({type:e,arrayOfIds:t,eventId:n})=>{const[s,c]=(0,a.useState)(!1);return{isDownloading:s,handleExport:async a=>{try{c(!0);const s=await(0,o.R)({type:e,format:a,ids:t,eventId:n});"json"===a&&(0,l.P)(JSON.stringify(s,null,2),`${e}.json`,"application/json"),"csv"===a&&(0,l.P)(s,`${e}.csv`,"text/csv"),(0,i.doAction)("eventin_notification",{type:"success",message:(0,r.__)("Exported successfully","eventin")})}catch(e){console.error(e),(0,i.doAction)("eventin_notification",{type:"error",message:e?.message||(0,r.__)("Export failed","eventin")})}finally{c(!1)}}}}},696781(e,t,n){n.d(t,{P:()=>a});const a=(e,t,n)=>{const a=new Blob([e],{type:n}),i=URL.createObjectURL(a),r=document.createElement("a");r.href=i,r.download=t,r.click(),URL.revokeObjectURL(i)}},784174(e,t,n){n.d(t,{A:()=>v});var a=n(951609),i=n(601455),r=n.n(i),o=n(986087),l=n(152619),s=n(527723),c=n(832099),d=n(981029),p=n(607638),m=n(300500),u=n(354725);const{Dragger:g}=d.A,v=e=>{const{type:t,paramsKey:n,shouldShow:i,revalidateList:d}=e||{},[v,f]=(0,o.useState)([]),[h,x]=(0,o.useState)(!1),[E,b]=(0,o.useState)(!1),_=()=>{b(!1)},y=`/eventin/v2/${t}/import`,w=(0,o.useCallback)(async e=>{try{x(!0);const t=await r()({path:y,method:"POST",body:e});return(0,l.doAction)("eventin_notification",{type:"success",message:(0,s.__)(` ${t?.message} `,"eventin")}),d(!0),f([]),x(!1),_(),t?.data||""}catch(e){throw x(!1),(0,l.doAction)("eventin_notification",{type:"error",message:e.message}),console.error("API Error:",e),e}},[t]),A={name:"file",accept:".json, .csv",multiple:!1,maxCount:1,onRemove:e=>{const t=v.indexOf(e),n=v.slice();n.splice(t,1),f(n)},beforeUpload:e=>(f([e]),!1),fileList:v},k=i?()=>window.open("https://themewinter.com/eventin/pricing/","_blank"):()=>b(!0);return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(c.A,{title:i?(0,s.__)("Upgrade to Pro","eventin"):(0,s.__)("Import data","eventin")},(0,a.createElement)(p.Ay,{className:"etn-import-btn eventin-import-button",variant:p.Vt,sx:{display:"flex",alignItems:"center",borderColor:"#d9d9d9",fontSize:"14px",fontWeight:400,color:"#64748B",height:"36px",padding:"10px",borderTopLeftRadius:"0px",borderBottomLeftRadius:"0px"},onClick:k},(0,a.createElement)(u.ImportIcon,{width:16,height:16}),i&&(0,a.createElement)(u.ProFlagIcon,null))),(0,a.createElement)(m.A,{title:(0,s.__)("Import file","eventin"),open:E,onCancel:_,maskClosable:!1,footer:null,centered:!0,destroyOnHidden:!0,wrapClassName:"etn-import-modal-wrap",className:"etn-import-modal-container eventin-import-modal-container"},(0,a.createElement)("div",{className:"etn-import-file eventin-import-file-container",style:{marginTop:"25px"}},(0,a.createElement)(g,{...A},(0,a.createElement)("p",{className:"ant-upload-drag-icon"},(0,a.createElement)(u.UploadCloudIcon,{width:"50",height:"50"})),(0,a.createElement)("p",{className:"ant-upload-text"},(0,s.__)("Click or drag file to this area to upload","eventin")),(0,a.createElement)("p",{className:"ant-upload-hint"},(0,s.__)("Choose a JSON or CSV file to import","eventin")),0!=v.length&&(0,a.createElement)(p.Ay,{onClick:async e=>{e.preventDefault(),e.stopPropagation();const t=new FormData;t.append(n,v[0],v[0].name),await w(t)},disabled:0===v.length,loading:h,variant:p.zB,className:"eventin-start-import-button"},h?(0,s.__)("Importing","eventin"):(0,s.__)("Start Import","eventin"))))))}},837486(e,t,n){n.d(t,{W:()=>d});var a=n(951609),i=n(769815),r=n(892911),o=n(747152),l=n(106390);const s=i.default.div`
	border-radius: 6px;
	background-color: white;
	border: 1px solid #d9dde3;
	display: flex;
	flex-direction: column;
	margin-bottom: 20px;
	.eventin-filter-header {
		padding: 16px;

		@media ( max-width: 576px ) {
			padding: 10px;
		}

		.eventin-filter-button {
			font-size: 14px;
			color: #e4e4e7;
			font-weight: normal;
			line-height: 0px;
			border-radius: 8px;
		}
	}

	.ant-select-selector {
		border-radius: 8px;
	}
`,c=(0,i.default)(o.A)`
	border-top: 1px solid #ebeef5;
	padding: ${({isFiltered:e})=>e?"12px 20px":"0 20px"};
	align-items: center;

	max-height: ${({isFiltered:e})=>e?"200px":"0"};
	opacity: ${({isFiltered:e})=>e?1:0};
	transform: ${({isFiltered:e})=>e?"translateY(0)":"translateY(-6px)"};
	overflow: hidden;
	transition:
		max-height 0.3s ease,
		opacity 0.3s ease,
		transform 0.3s ease,
		padding 0.3s ease;
`,d=({isFiltered:e,filteredTopMenu:t,filteredOptions:n=!1})=>(0,a.createElement)(s,null,(0,a.createElement)(r.A,{justify:"space-between",align:"center",className:"eventin-filter-header",wrap:!0,gap:16},t),(0,a.createElement)(l.If,{condition:n},(0,a.createElement)(c,{gutter:[16,16],isFiltered:e},n)))},780949(e,t,n){n.d(t,{A:()=>d});var a=n(951609),i=n(986087),r=n(527723),o=n(832099),l=n(484976),s=n(354725),c=n(105042);const d=({views:e=[{value:"list",label:(0,r.__)("List View","eventin"),icon:s.ListOutlined},{value:"calendar",label:(0,r.__)("Calendar View","eventin"),icon:s.CalendarIcon}],defaultView:t="list",paramKey:n="view",onChange:d,setView:p,sx:m={}})=>{const[u,g]=(0,l.useSearchParams)(),v=u.get(n)||t;(0,i.useEffect)(()=>{v&&p(v)},[v]);const f=(0,i.useCallback)(e=>{e!==v&&(d&&d(e),g(t=>{const a=new URLSearchParams(t);return a.set(n,e),a}))},[v,n,g,d]);return(0,a.createElement)(c.G,{style:m},e.map(e=>{const t=e.icon,n=v===e.value;return(0,a.createElement)(o.A,{key:e.value,title:e.label,placement:"bottom"},(0,a.createElement)(c.y,{type:"button",isActive:n,onClick:t=>f(e.value,t),"aria-label":e.label,"aria-pressed":n},(0,a.createElement)(t,{width:16,height:16})))}))}},105042(e,t,n){n.d(t,{G:()=>i,y:()=>r});var a=n(769815);const i=a.default.div`
	display: inline-flex;
	align-items: center;
	border-radius: 4px;
	padding: 2px;
	border: 1px solid #d9d9d9;
	height: 36px;
`,r=a.default.button`
	display: flex;
	align-items: center;
	justify-content: center;
	width: 32px;
	height: 32px;
	padding: 0;
	border: none;
	background: ${({isActive:e})=>e?"#F5F0FF":"transparent"};

	cursor: pointer;
	transition: all 0.2s ease;
	color: ${({isActive:e})=>e?"#6B2EE5":"#64748B"};
	outline: none;

	&:hover {
		background: ${({isActive:e})=>e?"#F5F0FF":"#F8FAFC"};
		color: ${({isActive:e})=>e?"#6B2EE5":"#334155"};
	}

	&:active {
		transform: scale( 0.95 );
	}

	&:focus-visible {
		outline: 2px solid #6b2ee5;
		outline-offset: 2px;
	}

	.anticon {
		font-size: 16px;
	}
`},625576(e,t,n){n.d(t,{A:()=>h});var a=n(951609),i=n(527723),r=n(547143),o=n(986087),l=n(152619),s=n(892911),c=n(649111),d=n(607638),p=n(406836),m=n(105028),u=n(864282);const g=[{label:(0,i.__)("Delete","eventin"),value:"delete"},{label:(0,i.__)("Publish","eventin"),value:"publish"},{label:(0,i.__)("Draft","eventin"),value:"draft"}],v={delete:(0,i.__)("Events deleted successfully","eventin"),publish:(0,i.__)("Events published successfully","eventin"),draft:(0,i.__)("Events drafted successfully","eventin")},f={delete:(0,i.__)("Failed to delete events","eventin"),publish:(0,i.__)("Failed to publish events","eventin"),draft:(0,i.__)("Failed to draft events","eventin")},h=({invalidateEventList:e,selectedEventsKey:t="selectedEvents",paramsKey:n="eventParams",loadingKey:h="eventActionLoading"})=>{const x=(0,r.useSelect)(e=>e(m.EF).getEventState(),[]),{setEventState:E}=(0,r.useDispatch)(m.EF),b=x[t],_=x[n],y=x[h],[w,A]=(0,o.useState)(null),k=(0,o.useCallback)(()=>{E({[t]:[],[n]:{..._,paged:1},[h]:!1}),A(null)},[_,E,t,n,h]),C=(0,o.useCallback)((e,t)=>{(0,l.doAction)("eventin_notification",{type:e,message:t})},[]),S=(0,o.useCallback)(async()=>{if(b.length)try{E({[h]:!0});const t=(0,p.generateBulkDeleteQueryString)(b);await u.A.events.deleteEvent(t),k(),e(),C("success",v.delete)}catch(e){console.error("Bulk delete error:",e),E({[h]:!1}),C("error",f.delete)}},[b,E,k,e,C,h]),F=(0,o.useCallback)(async t=>{if(b.length)try{E({[h]:!0}),await u.A.events.bulkUpdateEventStatus({event_ids:b,status:t}),k(),e(),C("success",v[t])}catch(e){console.error(`Bulk ${t} error:`,e),E({[h]:!1}),C("error",f[t])}},[b,E,k,e,C,h]),R=(0,o.useMemo)(()=>({delete:S,publish:()=>F("publish"),draft:()=>F("draft")}),[S,F]),L=(0,o.useCallback)(()=>{w&&R[w]&&R[w]()},[w,R]);return(0,a.createElement)(s.A,{gap:8},(0,a.createElement)(c.cL,{value:w,onChange:A,options:g,placeholder:(0,i.__)("Bulk Actions","eventin"),allowClear:!0,disabled:y}),(0,a.createElement)(d.Ay,{variant:d.TB,onClick:L,loading:y,sx:{height:"36px",borderRadius:"4px"},disabled:!w},(0,i.__)("Apply","eventin")))}},202659(e,t,n){n.d(t,{A:()=>c});var a=n(951609),i=n(527723),r=n(547143),o=n(357933),l=n(105028),s=n(110012);const c=({invalidateEventList:e,paramsKey:t="eventParams"})=>{const n=(0,r.useSelect)(e=>e(l.EF).getEventState()),{setEventState:c}=(0,r.useDispatch)(l.EF),d=n[t],p=(0,o.useDebounce)(n=>{c({[t]:{...d,search:n.target.value||""}}),e()},500);return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(s.DO,{placeholder:(0,i.__)("Search event by name","eventin"),onChange:p,allowClear:!0}))}},409617(e,t,n){n.d(t,{A:()=>p});var a=n(951609),i=(n(527723),n(547143)),r=n(986087),o=n(892911),l=n(664464),s=n(784174),c=n(105028),d=n(106390);const p=()=>{const{selectedEvents:e}=(0,i.useSelect)(e=>e(c.EF).getEventState()),t=!!e?.length,{invalidateResolution:n}=(0,i.useDispatch)("eventin/global"),p=(0,r.useCallback)(()=>{n("getEventList")},[n]);return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(o.A,{justify:"end",gap:8},(0,a.createElement)(d.If,{condition:!t},(0,a.createElement)(o.A,{gap:0},(0,a.createElement)(l.A,{type:"events",isSelectingItems:t}),(0,a.createElement)(s.A,{type:"events",paramsKey:"event_import",revalidateList:p}))),(0,a.createElement)(d.If,{condition:t},(0,a.createElement)(o.A,{justify:"end",gap:8},(0,a.createElement)(l.A,{type:"events",isSelectingItems:t,arrayOfIds:e})))))}},959720(e,t,n){n.d(t,{A:()=>_});var a=n(951609),i=n(527723),r=n(986087),o=n(547143),l=n(892911),s=n(340372),c=n(144290),d=n(247767),p=n(837486),m=n(625576),u=n(587716),g=n(202659),v=n(607638),f=n(409617),h=n(780949),x=n(105028),E=n(75093);const{useBreakpoint:b}=s.Ay,_=({eventListView:e,invalidateEventList:t,selectedEventsKey:n="selectedEvents",paramsKey:s="eventParams",loadingKey:_="eventActionLoading"})=>{const{pathname:y}=(0,d.useLocation)(),[w,A]=(0,r.useState)(!1),{setEventState:k}=(0,o.useDispatch)(x.EF),C=window.localized_multivendor_data?.is_vendor?Number(window.localized_multivendor_data.is_vendor):void 0,{lg:S}=b();return(0,a.createElement)(p.W,{isFiltered:w,filteredTopMenu:(0,a.createElement)(a.Fragment,null,(0,a.createElement)(E.If,{condition:"calendar"!==e},(0,a.createElement)(m.A,{invalidateEventList:t,selectedEventsKey:n,paramsKey:s,loadingKey:_})),(0,a.createElement)(E.If,{condition:"calendar"===e},(0,a.createElement)("span",null)),(0,a.createElement)(l.A,{gap:10,wrap:!S,justify:S?"start":"space-between"},(0,a.createElement)(g.A,{invalidateEventList:t}),(0,a.createElement)(E.If,{condition:!y.includes("recurring")},(0,a.createElement)(E.If,{condition:!C},(0,a.createElement)(h.A,{setView:e=>k({eventListView:e}),onChange:e=>k({eventListView:e})}))),(0,a.createElement)(f.A,null),(0,a.createElement)(v.Ay,{variant:v.Rm,onClick:()=>A(!w),type:"filled",sx:{height:"36px"}},(0,a.createElement)(c.A,{width:"16",height:"16"}),(0,i.__)("Filter","eventin")))),filteredOptions:(0,a.createElement)(u.A,{invalidateEventList:t,paramsKey:s})})}},380024(e,t,n){n.d(t,{A:()=>o});var a=n(951609),i=n(484976),r=n(607638);function o(e){const{record:t}=e;return(0,a.createElement)(i.Link,{to:`/events/edit/${t.id}/basic`},(0,a.createElement)(r.vQ,{variant:r.Vt}))}},673401(e,t,n){n.d(t,{A:()=>m});var a=n(951609),i=n(354725),r=n(327154),o=n(864282),l=n(986087),s=n(152619),c=n(527723),d=n(892911),p=n(819549);function m(e){const{id:t,modalOpen:n,setModalOpen:m}=e,[u,g]=(0,l.useState)(!1);return(0,a.createElement)(p.A,{centered:!0,title:(0,a.createElement)(d.A,{gap:10},(0,a.createElement)(i.DiplomaIcon,null),(0,a.createElement)("span",null,(0,c.__)("Are you sure?","eventin"))),open:n,onOk:async()=>{g(!0);try{const e=await o.A.events.sendEmailToAllAttendees({event_id:t});e?.message?.includes("success")||e?.message?.includes("Success")?((0,s.doAction)("eventin_notification",{type:"success",message:(0,c.__)("Successfully Sent Email to all Attendees for this event!","eventin")}),m(!1)):((0,s.doAction)("eventin_notification",{type:"error",message:e.message}),m(!1))}catch(e){console.error("Error in Email Sending!",e),(0,s.doAction)("eventin_notification",{type:"error",message:(0,c.__)("Failed to send email to all attendees!","eventin")})}finally{g(!1)}},confirmLoading:u,onCancel:()=>m(!1),okText:"Send",okButtonProps:{type:"default",style:{height:"32px",fontWeight:600,fontSize:"14px",color:r.PRIMARY_COLOR,border:`1px solid ${r.PRIMARY_COLOR}`}},cancelButtonProps:{style:{height:"32px"}},cancelText:"Cancel",width:"344px"},(0,a.createElement)("p",null,(0,c.__)("Are you sure you want to send email to all attendees for this event?","eventin")))}},196186(e,t,n){n.d(t,{A:()=>v});var a=n(951609),i=n(354725),r=n(327154),o=n(864282),l=n(929491),s=n(547143),c=n(152619),d=n(527723),p=n(892911),m=n(819549),u=n(986087);const g=(0,s.withDispatch)(e=>{const t=e("eventin/global");return{setShouldRevalidateEventList:e=>{t.setRevalidateEventList(e),t.invalidateResolution("getEventList")}}}),v=(0,l.compose)(g)(function(e){const{id:t,modalOpen:n,setModalOpen:l,setShouldRevalidateEventList:s}=e,[g,v]=(0,u.useState)(!1);return(0,a.createElement)(m.A,{centered:!0,title:(0,a.createElement)(p.A,{gap:10},(0,a.createElement)(i.DuplicateIcon,null),(0,a.createElement)("span",null,(0,d.__)("Are you sure?","eventin"))),open:n,onOk:async()=>{v(!0);try{await o.A.events.cloneEvent(t),(0,c.doAction)("eventin_notification",{type:"success",message:(0,d.__)("Successfully cloned the event!","eventin")}),l(!1),s(!0)}catch(e){console.error("Error in Bulk Deletion!",e),(0,c.doAction)("eventin_notification",{type:"error",message:(0,d.__)("Failed to clone the event!","eventin")})}finally{v(!1)}},confirmLoading:g,onCancel:()=>l(!1),okText:"Clone",okButtonProps:{type:"default",style:{height:"32px",fontWeight:600,fontSize:"14px",color:r.PRIMARY_COLOR,border:`1px solid ${r.PRIMARY_COLOR}`}},cancelButtonProps:{style:{height:"32px"}},cancelText:"Cancel",width:"344px"},(0,a.createElement)("p",null,(0,d.__)("Are you sure you want to clone this event?","eventin")))})},220542(e,t,n){n.d(t,{NH:()=>x,mN:()=>E});var a=n(951609),i=n(527723),r=n(547143),o=n(484976),l=n(247767),s=n(354725),c=n(276633),d=n(862949),p=n(105028);const m=16,u={type:"divider"};function g(e){return{label:(0,i.__)("Clone","eventin"),key:"0",icon:(0,a.createElement)(s.CloneOutlined,{width:m,height:m}),className:"copy-event",onClick:e.openCloneModal}}function v(e,t=!1){if(!e.isAttendeeRegistration||!e.hasAttendeePermission)return null;const n=`/attendees/${t?"event/":""}${e.record.id}`;return{label:(0,a.createElement)(o.Link,{to:n},(0,i.__)("Attendees","eventin")),key:"2",icon:(0,a.createElement)(s.ParticipantsIcon,{width:m,height:m}),className:"action-dropdown-item"}}function f(e){return{label:(0,i.__)("Delete","eventin"),key:"7",icon:(0,a.createElement)(s.DeleteOutlined,{width:m,height:m}),className:"delete-event",onClick:e.showConfirm}}function h(e){return 0===e.record.parent&&"yes"===e.record.recurring_enabled?{label:(0,i.__)("Recurring list","eventin"),key:"11",icon:(0,a.createElement)(c.v,{width:m,height:m}),className:"edit-event",onClick:()=>{e.setEventState({recurringParentId:e.record.id}),e.navigate("/events/recurring/"+e.record.id)}}:null}function x(e){const t=g(e),n=v(e),o=function(e){return{label:e.isHomepage?(0,i.__)("Remove from Homepage","eventin"):(0,i.__)("Set as Homepage","eventin"),key:"9",icon:(0,a.createElement)(d.A,{width:"16",height:"16",strokeColor:"currentColor",fillColor:"none"}),className:"set-event-as-homepage",onClick:e.setEventAsHomePage}}(e),s=h(e),c=f(e),{recurringParentId:m}=(0,r.useSelect)(e=>e(p.EF).getEventState()),{pathname:x}=(0,l.useLocation)();return m&&"/events"!==x?[n,o,s,u,c].filter(Boolean):[t,u,n,o,s,u,c].filter(Boolean)}function E(e){const t=g(e),n=function(e){return e.isAttendeeRegistration&&e.isPermissions?{label:(0,a.createElement)("a",{href:`${e.scanTicketURL}${e.record.id}`},(0,i.__)("Scan Ticket","eventin-pro")),key:"4",icon:(0,a.createElement)(s.ScanTicketIcon,{width:m,height:m}),className:"action-dropdown-item"}:null}(e);return[t,n,v(e,!0),h(e),f(e)].filter(Boolean)}},926108(e,t,n){n.d(t,{A:()=>c});var a=n(951609),i=n(769815),r=n(354725),o=n(607638),l=n(300500),s=n(110012);function c(e){const{scriptModalOpen:t,setScriptModalOpen:n}=e,c=window?.localized_data_obj?.site_url,d=`<script src="${c}/eventin-external-script?id=${e?.record?.id}"><\/script>`,p=i.default.div`
		content: '';
		position: absolute;
		width: 100px;
		height: 30px;
		top: 4px;
		right: 40px;
		z-index: 1;
		background-image: linear-gradient(
			to right,
			rgba( 255, 255, 255, 0.3 ) 50%,
			rgb( 255, 255, 255 ) 75%
		);
	`;return(0,a.createElement)(l.A,{title:"Get Script",open:t,onCancel:()=>n(!1),footer:null,width:"600px",destroyOnHidden:!0,maskClosable:!1},(0,a.createElement)("div",{style:{position:"relative"}},(0,a.createElement)(s.ks,{value:d,readOnly:!0}),(0,a.createElement)(o.i8,{copy:d,variant:{type:"ghost",size:"large"},sx:{position:"absolute",top:" 1px",right:" 1px",zIndex:100,height:"38px",borderRadius:"6px",width:"38px",backgroundColor:"#F0EAFC"},icon:(0,a.createElement)(r.CopyFillIcon,null)}),(0,a.createElement)(p,null)))}},29802(e,t,n){n.d(t,{A:()=>d});var a=n(951609),i=n(527723),r=n(790070),o=n(832099),l=n(782481),s=n(380024),c=n(244102);function d(e){const{record:t}=e;return(0,a.createElement)(r.A,{size:"small",className:"event-actions"},(0,a.createElement)(o.A,{title:(0,i.__)("Preview","eventin")},(0,a.createElement)(c.A,{record:t})),(0,a.createElement)(o.A,{title:(0,i.__)("Edit","eventin")},(0,a.createElement)(s.A,{record:t})),(0,a.createElement)(o.A,{title:(0,i.__)("More Actions","eventin")},(0,a.createElement)(l.A,{record:t})))}},782481(e,t,n){n.d(t,{A:()=>k});var a=n(951609),i=n(247767),r=n(17437),o=n(811721),l=n(900428),s=n(929491),c=n(547143),d=n(152619),p=n(354725),m=n(607638),u=n(910962),g=n(196186),v=n(926108),f=n(439992),h=n(673401),x=n(337007),E=n(220542),b=n(75093),_=n(649111),y=n(105028);const w=(0,c.withSelect)(e=>{const t=e("eventin/global");return{settings:t.getSettings(),userPermissions:t.getUserPermissions(),isSettingsLoading:t.isResolving("getSettings")}}),A=(0,c.withDispatch)(e=>{const t=e("eventin/global"),n=e("core"),a=e(y.EF);return{setShouldRevalidateEventList:e=>{t.setRevalidateEventList(e),t.invalidateResolution("getEventList"),n.invalidateResolution("getEntityRecord",["root","site"])},invalidateEventRecurringList:()=>{const{recurringParentId:e}=(0,c.select)(y.EF).getEventState();a.invalidateResolution("getEventRecurringList",[e])}}}),k=(0,s.compose)([w,A])(function(e){const{setShouldRevalidateEventList:t,invalidateEventRecurringList:n,record:s,settings:y,isSettingsLoading:w,userPermissions:A,fromCalendar:k=!1}=e,C=(0,i.useNavigate)(),{setEventState:S}=(0,c.useDispatch)("eventin/events"),{modalState:F,permissions:R,settingsFlags:L,handlers:I,homepage:P,scanTicketURL:O,openCloneModal:z}=(0,x.Z)({record:s,settings:y,userPermissions:A,setShouldRevalidateEventList:t,invalidateEventRecurringList:n}),N={record:s,isHomepage:P.isHomepage,isAttendeeRegistration:L.isAttendeeRegistration,hasAttendeePermission:R.hasAttendeePermission,hasBookingPermission:R.hasBookingPermission,isPermissions:R.isPermissions,openCloneModal:z,setEventAsHomePage:I.setEventAsHomePage,showConfirm:I.showConfirm,scanTicketURL:O,setEventState:S,navigate:C},M=(0,E.NH)(N),D=(0,E.mN)(N),B=(0,d.applyFilters)("eventin-pro-event-list-menu-items",M,s,L.isRsvpActive,L.isAttendeeRegistration,F.setScriptModalOpen,F.setCertificateModalOpen,F.setEmailAllAttendeesModalOpen,C,R.hasBookingsPermission,R.isPermissions),$=window.localized_multivendor_data?.is_vendor?Number(window.localized_multivendor_data.is_vendor):void 0,T=(0,d.applyFilters)("eventin-pro-event-list-menu-items",D,s,L.isRsvpActive,L.isAttendeeRegistration,F.setScriptModalOpen,F.setCertificateModalOpen,F.setEmailAllAttendeesModalOpen,C,R.hasBookingsPermission,R.isPermissions),j=$?T:B;return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(r.mL,{styles:u.wV}),(0,a.createElement)(o.A,{menu:{items:j},trigger:["click"],placement:"bottomRight",overlayClassName:"action-dropdown"},(0,a.createElement)("div",null,(0,a.createElement)(b.If,{condition:!k},(0,a.createElement)(m.Ay,{variant:m.Vt,disabled:w},(0,a.createElement)(l.A,{spinning:w,size:"small"},(0,a.createElement)(p.MoreIconOutlined,{width:"16",height:"16"})))),(0,a.createElement)(b.If,{condition:k},(0,a.createElement)(_.ve,{type:"filled",disabled:w},(0,a.createElement)(l.A,{spinning:w,size:"small"},(0,a.createElement)(p.MoreIconOutlined,{width:"16",height:"16"})))))),(0,a.createElement)(v.A,{scriptModalOpen:F.scriptModalOpen,setScriptModalOpen:F.setScriptModalOpen,record:s,form:!0}),(0,a.createElement)(f.A,{id:s.id,modalOpen:F.certificateModalOpen,setModalOpen:F.setCertificateModalOpen}),(0,a.createElement)(g.A,{id:s.id,modalOpen:F.cloneModalOpen,setModalOpen:F.setCloneModalOpen}),(0,a.createElement)(h.A,{id:s.id,modalOpen:F.emailAllAttendeesModalOpen,setModalOpen:F.setEmailAllAttendeesModalOpen}))})},244102(e,t,n){n.d(t,{A:()=>o});var a=n(951609),i=n(354725),r=n(650400);function o(e){const{record:t}=e;return(0,a.createElement)(r.Ay,{variant:"filled",onClick:()=>window.open(`${t.link}`,"_blank"),target:"_blank",icon:(0,a.createElement)(i.ExternalLinkOutlined,{width:"16",height:"16"})})}},439992(e,t,n){n.d(t,{A:()=>m});var a=n(951609),i=n(354725),r=n(327154),o=n(864282),l=n(986087),s=n(152619),c=n(527723),d=n(892911),p=n(819549);function m(e){const{id:t,modalOpen:n,setModalOpen:m}=e,[u,g]=(0,l.useState)(!1);return(0,a.createElement)(p.A,{centered:!0,title:(0,a.createElement)(d.A,{gap:10},(0,a.createElement)(i.DiplomaIcon,null),(0,a.createElement)("span",null,(0,c.__)("Are you sure?","eventin"))),open:n,onOk:async()=>{g(!0);try{const e=await o.A.events.sendCertificate(t);e?.message?.includes("success")||e?.message?.includes("Success")?((0,s.doAction)("eventin_notification",{type:"success",message:(0,c.__)("Successfully Sent Certificate for this event!","eventin")}),m(!1)):((0,s.doAction)("eventin_notification",{type:"error",message:e.message}),m(!1))}catch(e){console.error("Error in Certificate Sending!",e),(0,s.doAction)("eventin_notification",{type:"error",message:(0,c.__)("Failed to send certificate!","eventin")})}finally{g(!1)}},confirmLoading:u,onCancel:()=>m(!1),okText:"Send",okButtonProps:{type:"default",style:{height:"32px",fontWeight:600,fontSize:"14px",color:r.PRIMARY_COLOR,border:`1px solid ${r.PRIMARY_COLOR}`}},cancelButtonProps:{style:{height:"32px"}},cancelText:"Cancel",width:"344px"},(0,a.createElement)("p",null,(0,c.__)("Are you sure you want to send certificate for this event?","eventin")))}},337007(e,t,n){n.d(t,{Z:()=>p});var a=n(986087),i=n(547143),r=n(152619),o=n(527723),l=n(357933),s=n(80734),c=n(864282),d=n(105028);function p(e){const{setShouldRevalidateEventList:t,invalidateEventRecurringList:n,record:p,settings:m,userPermissions:u}=e,[g,v]=(0,a.useState)(""),[f,h]=(0,a.useState)(!1),[x,E]=(0,a.useState)(!1),[b,_]=(0,a.useState)(!1),y=Boolean(m?.attendee_registration),w=Boolean(m?.modules?.rsvp&&"on"===m?.modules?.rsvp),{recurringParentId:A}=(0,i.useSelect)(e=>e(d.EF).getEventState(),[]),k=(0,i.useSelect)(e=>{var t;const n=e("core").getEntityRecord("root","site");return null!==(t=n?.page_on_front)&&void 0!==t?t:null},[]),C=null!=k&&null!=p?.id&&Number(p.id)===Number(k),{isPermissions:S}=(0,l.usePermissionAccess)("etn_manage_qr_scan")||{},F=Boolean(u?.permissions?.includes("etn_manage_order")),R=u?.is_super_admin||u?.permissions?.includes?.("etn_manage_order"),L=u?.is_super_admin||u?.permissions?.includes?.("etn_manage_attendee"),I=async()=>{try{await c.A.events.deleteEvent(p.id),A?n():t(!0),(0,r.doAction)("eventin_notification",{type:"success",message:(0,o.__)("Successfully deleted the event!","eventin")})}catch(e){console.error("Error deleting event",e),(0,r.doAction)("eventin_notification",{type:"error",message:(0,o.__)("Failed to delete the event!","eventin")})}};return{modalState:{scriptModalOpen:g,setScriptModalOpen:v,certificateModalOpen:f,setCertificateModalOpen:h,cloneModalOpen:x,setCloneModalOpen:E,emailAllAttendeesModalOpen:b,setEmailAllAttendeesModalOpen:_},permissions:{hasBookingPermission:F,hasBookingsPermission:R,hasAttendeePermission:L,isPermissions:S},settingsFlags:{isAttendeeRegistration:y,isRsvpActive:w},handlers:{handleDelete:I,setEventAsHomePage:async()=>{try{const e=await c.A.events.setEventAsHomePage(p.id);t(!0),(0,r.doAction)("eventin_notification",{type:"success",message:e?.message||(0,o.__)("Operation successful!","eventin")})}catch(e){(0,r.doAction)("eventin_notification",{type:"error",message:e?.message||(0,o.__)("Failed to set event as homepage!","eventin")})}},showConfirm:()=>{(0,s.A)({title:(0,o.__)("Are you sure?","eventin"),content:(0,o.__)("Are you sure you want to delete this event?","eventin"),onOk:I})}},homepage:{isHomepage:C},scanTicketURL:(null!==(P=window.localized_data_obj?.admin_url)&&void 0!==P?P:"")+"edit.php?post_type=etn-attendee&etn_action=ticket_scanner&event_id=",openCloneModal:()=>E(!0)};var P}},452034(e,t,n){n.d(t,{Y:()=>p});var a=n(951609),i=n(527723),r=n(18537),o=n(544606),l=n(534654),s=n(632964),c=n(882378),d=n(29802);const p=[{title:(0,i.__)("Event","eventin"),dataIndex:"title",key:"title",width:"40%",render:(e,t)=>(0,a.createElement)(s.A,{text:(0,r.decodeEntities)(e),record:t})},{title:(0,i.__)("Sold","eventin"),dataIndex:"sold",key:"sold",render:(e,t)=>(0,a.createElement)(o.A,{record:t})},{title:(0,i.__)("Revenue","eventin"),dataIndex:"revenue",key:"revenue",render:(e,t)=>(0,a.createElement)(c.A,{record:t})},{title:(0,i.__)("Status","eventin"),dataIndex:"status",key:"status",render:(e,t)=>(0,a.createElement)(l.A,{status:e,record:t})},{title:(0,i.__)("Action","eventin"),key:"action",width:120,render:(e,t)=>(0,a.createElement)(d.A,{record:t})}]},882378(e,t,n){n.d(t,{A:()=>o});var a=n(951609),i=n(406836),r=n(649111);function o(e){const{record:t}=e,n=t.revenue||0,{currency_position:o,decimals:l,decimal_separator:s,thousand_separator:c,currency_symbol:d}=window?.localized_data_obj||{};return(0,a.createElement)(r.Wd,null,(0,i.formatSymbolDecimalsPrice)(Number(n),l,o,s,c,d))}},544606(e,t,n){n.d(t,{A:()=>r});var a=n(951609),i=n(527723);function r(e){var t;const{record:n}=e,r={fontWeight:500,fontSize:"14px"};if(n.etn_enable_global_stock){const e=Number(n.total_sold_tickets)||0,t=Number(n.etn_global_stock)||0;return(0,a.createElement)("span",{style:r},e," / ",t)}const o=Object.values(null!==(t=n.sold_ticket_count)&&void 0!==t?t:{}).reduce((e,t)=>e+t,0)||0,l=Number(n.total_ticket),s=-1===l?(0,i.__)("Unlimited","eventin"):l;return(0,a.createElement)("span",{style:r},`${o} / ${s}`)}},534654(e,t,n){n.d(t,{A:()=>d});var a=n(951609),i=n(547143),r=n(527723),o=n(892911),l=n(832099),s=n(862949),c=n(649111);function d(e){const{record:t}=e,n=(0,i.useSelect)(e=>{const t=e("core").getEntityRecord("root","site");return t?.page_on_front||null}),d="object"==typeof t.status?t.status.key:t.status.toLowerCase(),p="object"==typeof t.status?t.status.value:t.status,m={upcoming:{background:"#E5F3FF",text:"#1890FF",border:"#E5F3FF"},draft:{background:"#F5F5F5",text:"#8C8C8C",border:"#F5F5F5"},published:{background:"#D4F4E2",text:"#00B96B",border:"#D4F4E2"},private:{background:"#FFF7E6",text:"#FA8C16",border:"#FFF7E6"},completed:{background:"#FFE5E6",text:"#FF4D4F",border:"#FFE5E6"},ongoing:{background:"#D4F4E2",text:"#00B96B",border:"#D4F4E2"},expired:{background:"#FFE5E6",text:"#FF4D4F",border:"#FFE5E6"}}[d]||{background:"#F5F5F5",text:"#8C8C8C",border:"#F5F5F5"},u=n&&parseInt(t.id)===parseInt(n);return(0,a.createElement)(o.A,{align:"center",gap:"16"},(0,a.createElement)(c.eU,{background:m.background,text:m.text},p),u&&(0,a.createElement)(l.A,{title:(0,r.__)("Current Homepage","eventin")},(0,a.createElement)("div",null,(0,a.createElement)(s.A,{style:{marginLeft:"4px"}}))))}},632964(e,t,n){n.d(t,{A:()=>u});var a=n(951609),i=n(547143),r=n(527723),o=n(484976),l=n(247767),s=n(832099),c=n(354725),d=n(406836),p=n(75093),m=n(649111);function u(e){const{text:t,record:n}=e,{setEventState:u}=(0,i.useDispatch)("eventin/events"),g=(0,d.getWordpressFormattedDate)(n?.start_date)+`, ${(0,d.getWordpressFormattedTime)(n?.start_time)} `,v=Boolean(n?.password)?(0,r.sprintf)((0,r.__)("Protected: %s","eventin"),t):t,f=(0,l.useNavigate)();return(0,a.createElement)(m._q,null,(0,a.createElement)("div",{className:"event-thumbnail"},(0,a.createElement)(p.If,{condition:n?.event_banner},(0,a.createElement)("img",{src:n.event_banner,alt:t,className:"event-thumbnail-image"})),(0,a.createElement)(p.If,{condition:!n?.event_banner},(0,a.createElement)("img",{src:(0,d.assetURL)("/images/event_thumbnail_demo_avatar.webp"),alt:t,className:"event-thumbnail-image"}))),(0,a.createElement)("div",{className:"event-details"},(0,a.createElement)(o.Link,{className:"event-title",to:`/events/edit/${n.id}/basic`},v),(0,a.createElement)(p.If,{condition:n?.location},(0,a.createElement)("p",{className:"event-location"},(0,d.getLocationInfo)(n?.location),n?.location?.address?.address&&(0,a.createElement)(s.A,{title:(0,r.__)("There's a problem with this event's location. Kindly remove the location and save it again.","eventin")},(0,a.createElement)(c.ErrorAlertIcon,null)))),(0,a.createElement)("div",{className:"event-date-time-badges"},(0,a.createElement)(p.If,{condition:n.start_date&&n.start_time},(0,a.createElement)("span",null,g)),(0,a.createElement)(p.If,{condition:0===n.parent&&"yes"===n.recurring_enabled},(0,a.createElement)(s.A,{title:(0,r.__)("This is a recurring event. Click to view all occurrences.","eventin")},(0,a.createElement)("span",{className:"recurring-badge",onClick:()=>{return e=n?.id,u({recurringParentId:[e]}),void f("/events/recurring/"+e);var e}},(0,a.createElement)(c.RecurringEventIcon,null)," ",(0,r.__)("Recurring","eventin")))))))}},991486(e,t,n){n.d(t,{A:()=>p});var a=n(951609),i=n(527723),r=n(547143),o=n(929491),l=n(105028),s=n(649111),c=n(167821);const d=(0,r.withSelect)(e=>{const t=e(c.V);return{categoryList:t.getAllEventCategoryList(),isLoading:t.isResolving("getAllEventCategoryList")}}),p=(0,o.compose)(d)(({invalidateEventList:e,categoryList:t,isLoading:n,paramsKey:o="eventParams"})=>{const c=(0,r.useSelect)(e=>e(l.EF).getEventState()),{setEventState:d}=(0,r.useDispatch)(l.EF),p=c[o];return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(s.cL,{placeholder:(0,i.__)("Category","eventin"),options:Array.isArray(t?.items)?t?.items.map(e=>({label:e.name,value:e.id})):[],size:"default",value:p?.category,onChange:t=>{d({[o]:{...p,category:t}}),e()},virtual:!1,loading:n,allowClear:!0}))})},962418(e,t,n){n.d(t,{A:()=>p});var a=n(951609),i=n(527723),r=n(547143),o=n(974353),l=n.n(o),s=n(406836),c=n(105028),d=n(649111);const p=({invalidateEventList:e,paramsKey:t="eventParams"})=>{const n=(0,r.useSelect)(e=>e(c.EF).getEventState()),{setEventState:o}=(0,r.useDispatch)(c.EF),p=n[t];let m;return(p?.start_date||p?.end_date)&&(m=[p?.start_date?l()(p?.start_date):null,p?.end_date?l()(p?.end_date):null]),(0,a.createElement)(d.HJ,{size:"default",onChange:n=>{o({[t]:{...p,start_date:(0,s.dateFormatter)(n?.[0]||void 0),end_date:(0,s.dateFormatter)(n?.[1]||void 0)}}),e()},format:(0,s.getDateFormat)(),value:m,placeholder:[(0,i.__)("Start Date","eventin"),(0,i.__)("End Date","eventin")],allowClear:!0,styles:{root:{height:"36px !important",borderRadius:"4px !important"}}})}},481979(e,t,n){n.d(t,{A:()=>d});var a=n(951609),i=n(527723),r=n(547143),o=n(929491),l=n(105028),s=n(649111);const c=(0,r.withSelect)(e=>{const t=e("eventin/global");return{speakerList:t.getSpeakerList(),isLoading:t.isResolving("getSpeakerList")}}),d=(0,o.compose)(c)(({invalidateEventList:e,speakerList:t,isLoading:n,paramsKey:o="eventParams"})=>{const c=(0,r.useSelect)(e=>e(l.EF).getEventState()),{setEventState:d}=(0,r.useDispatch)(l.EF),p=c[o];return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(s.cL,{placeholder:(0,i.__)("Organizer","eventin"),options:Array.isArray(t?.items)?t?.items.map(e=>({label:e.name,value:e.id})):[],size:"default",value:p?.organizer,onChange:t=>{d({[o]:{...p,organizer:t}}),e()},virtual:!1,loading:n,allowClear:!0}))})},287832(e,t,n){n.d(t,{A:()=>c});var a=n(951609),i=n(527723),r=n(547143),o=n(105028),l=n(649111);const s=[{label:(0,i.__)("All","eventin"),value:"all"},{label:(0,i.__)("Draft","eventin"),value:"draft"},{label:(0,i.__)("Ongoing","eventin"),value:"ongoing"},{label:(0,i.__)("Upcoming","eventin"),value:"upcoming"},{label:(0,i.__)("Expired","eventin"),value:"past"}],c=({invalidateEventList:e,paramsKey:t="eventParams"})=>{const n=(0,r.useSelect)(e=>e(o.EF).getEventState()),{setEventState:c}=(0,r.useDispatch)(o.EF),d=n[t];return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(l.cL,{placeholder:(0,i.__)("Status","eventin"),options:s,value:d?.status,size:"default",onChange:n=>{c({[t]:{...d,status:n}}),e()},allowClear:!0}))}},207150(e,t,n){n.d(t,{A:()=>d});var a=n(951609),i=n(527723),r=n(547143),o=n(105028),l=n(401671),s=n(649111);const c=[{label:(0,i.__)("All Types","eventin"),value:"all"},{label:(0,i.__)("Online","eventin"),value:l.R.ONLINE},{label:(0,i.__)("Offline","eventin"),value:l.R.OFFLINE},{label:(0,i.__)("Hybrid","eventin"),value:l.R.HYBRID}],d=({invalidateEventList:e,paramsKey:t="eventParams"})=>{const n=(0,r.useSelect)(e=>e(o.EF).getEventState()),{setEventState:l}=(0,r.useDispatch)(o.EF),d=n[t];return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(s.cL,{placeholder:(0,i.__)("Event Type","eventin"),options:c,value:d?.type,size:"default",onChange:n=>{l({[t]:{...d,type:n}}),e()},allowClear:!0}))}},587716(e,t,n){n.d(t,{A:()=>_});var a=n(951609),i=n(892911),r=n(340372),o=n(929491),l=n(547143),s=n(527723),c=n(287832),d=n(207150),p=n(962418),m=n(481979),u=n(991486),g=n(354725),v=n(607638),f=n(105028),h=n(75093),x=n(327154);const{useBreakpoint:E}=r.Ay,b=(0,l.withDispatch)(e=>{const t=e("eventin/global");return{setShouldRevalidateEventList:e=>{t.setRevalidateEventList(e),t.invalidateResolution("getEventList")}}}),_=(0,o.compose)(b)(({invalidateEventList:e,paramsKey:t="eventParams"})=>{const n=(0,l.useSelect)(e=>e(f.EF).getEventState()),{setEventState:r}=(0,l.useDispatch)(f.EF),o=n[t],{lg:b}=E();return(0,a.createElement)(i.A,{justify:"space-between",align:"center",style:{width:"100%"}},(0,a.createElement)(i.A,{gap:10,wrap:!b},(0,a.createElement)(c.A,{invalidateEventList:e,paramsKey:t}),(0,a.createElement)(d.A,{invalidateEventList:e,paramsKey:t}),(0,a.createElement)(m.A,{invalidateEventList:e,paramsKey:t}),(0,a.createElement)(u.A,{invalidateEventList:e,paramsKey:t}),(0,a.createElement)(h.If,{condition:"calendar"!==n?.eventListView},(0,a.createElement)(p.A,{invalidateEventList:e,paramsKey:t}))),(0,a.createElement)(h.If,{condition:o?.status||o?.type||o?.category||o?.organizer||o?.start_date||o?.end_date},(0,a.createElement)(v.Ay,{variant:v.Rm,sx:{height:"36px",color:"#EF4444"},icon:(0,a.createElement)(g.ResetRedIcon,null),onClick:()=>(r({[t]:{paged:x.pagination.paged,per_page:x.pagination.per_page}}),void e())},(0,s.__)("Reset"))))})},649111(e,t,n){n.d(t,{B0:()=>E,HJ:()=>_,IL:()=>m,OI:()=>h,Us:()=>y,Wd:()=>d,XN:()=>u,_q:()=>c,cL:()=>s,eO:()=>x,eU:()=>p,iU:()=>f,s0:()=>g,ve:()=>b,xI:()=>v});var a=n(607638),i=n(769815),r=n(954861),o=n(236492);const{RangePicker:l}=r.A,s=(0,i.default)(o.A)`
	.ant-select-selector {
		height: 36px !important;
		border-radius: 4px;
		border: 1px solid #e5e7eb;
		background-color: #fff;
		color: #334155;
		font-size: 14px;
		width: 120px !important;
	}
`,c=((0,i.default)(l)`
	.ant-picker-range {
		height: 36px !important;
		border-radius: 4px !important;
	}
`,i.default.div`
	display: flex;
	gap: 12px;
	align-items: center;
	.event-thumbnail {
		width: 80px;
		height: 64px;
		border-radius: 4px;
		overflow: hidden;
		flex-shrink: 0;
		background-color: #f0f0f0;

		.event-thumbnail-image {
			width: 100%;
			height: 100%;
			object-fit: cover;
		}
	}
	.event-details {
		.event-title {
			color: #202223;
			font-size: 14px;
			font-weight: 500;
			line-height: 20px;
			display: inline-block;
			margin-bottom: 6px;
			text-decoration: none;
		}
		.event-location {
			color: #6d6d6d;
			font-weight: 400;
			margin: 0;
		}
		.event-date-time-badges {
			display: flex;
			align-items: center;
			gap: 4px;
			flex-wrap: wrap;
			font-size: 13px;
			color: #6d6d6d;
			.event-type {
				background-color: #e6f4ff;
				color: #0958d9;
				padding: 2px 8px;
				border-radius: 4px;
				font-size: 12px;
				font-weight: 500;
			}
			.recurring-badge {
				background-color: #e6f4ff;
				color: #0958d9;
				padding: 2px 8px;
				border-radius: 50px;
				font-size: 12px;
				font-weight: 500;
				margin-inline: 10px;
				display: flex;
				gap: 4px;
				cursor: pointer;
			}
		}
	}
`),d=i.default.span`
	font-size: 14px;
	font-weight: 500;
	color: #202223;
`,p=i.default.span`
	background-color: ${e=>e.background};
	color: ${e=>e.text};
	border-radius: 50px;
	padding: 6px 16px;
	min-width: 80px;
	text-align: center;
	font-weight: 500;
	font-size: 12px;
	line-height: 18px;
	text-transform: capitalize;
	white-space: nowrap;
	transition: all 0.2s ease;
`,m=i.default.div`
	background-color: #fff;
	border-radius: 12px;
	padding: 20px;
	margin: 0 auto;
	min-height: 500px;
	@media ( max-width: 900px ) {
		max-width: 100%;
		padding: 16px;
	}

	@media ( max-width: 600px ) {
		padding: 10px;
	}

	.ant-picker-calendar {
		max-width: 1440px;
		margin: 0 auto;

		@media ( max-width: 1200px ) {
			max-width: 100%;
		}

		@media ( max-width: 900px ) {
			max-width: 100%;
		}

		@media ( max-width: 600px ) {
			max-width: 100%;
		}

		.ant-picker-panel {
			border-top: none;
		}

		.ant-picker-calendar-header {
			display: none;
		}

		.ant-picker-calendar-date {
			border-top: none;
		}

		.ant-picker-content {
			thead {
				background-color: #f3f4f6;
				tr {
					&:hover {
						background-color: transparent !important;
					}
				}
				th {
					color: #64748b;
					font-weight: 500;
					font-size: 12px;
					text-transform: uppercase;
					text-align: center;
					padding: 10px 0 !important;
					border: 1px solid #e5e7eb;
					border-bottom: none;
				}
			}

			tbody tr {
				&:hover {
					background: transparent !important;
				}
			}
		}

		.ant-picker-cell {
			padding: 0;
			border: 1px solid #f0f0f0;
			vertical-align: top;

			&.ant-picker-calendar-date-today {
				&:hover {
					background: #f7f0ff !important;
				}
			}
		}

		.ant-picker-cell-in-view {
			.ant-picker-cell-inner {
				color: #334155;
			}
		}

		.ant-picker-cell-disabled {
			.ant-picker-cell-inner {
				color: #94a3b8;
			}
		}

		.ant-picker-cell-selected {
			.ant-picker-cell-inner {
				background: transparent;
			}
		}

		.ant-picker-cell-today {
			background-color: white;
			padding: 10px !important;

			.ant-picker-calendar-date-today {
				background-color: #6c1bea !important;
				width: 24px;
				height: 24px;
				font-size: 14px;
				border-radius: 100px;
				display: flex;
				align-items: center;
				justify-content: center;

				.ant-picker-calendar-date-value {
					color: white !important;
				}
			}
			.ant-picker-cell-inner::before {
				border: none;
			}

			.ant-picker-cell-inner {
				&::after {
					display: none;
				}
			}
		}

		.ant-picker-cell-inner {
			padding: 8px;
			height: 120px;
			background: transparent;
			border-radius: 0;
			display: flex;
			flex-direction: column;
			align-items: flex-start;
			position: relative;
			margin: 0 !important;

			.ant-picker-calendar-date-content {
				width: 100%;
				&::-webkit-scrollbar {
					display: none;
				}

				&::-webkit-scrollbar {
					width: 3px;
					padding-inline: 2px;
				}
				@media ( max-width: 576px ) {
					&::-webkit-scrollbar {
						display: none;
					}
				}
				&::-webkit-scrollbar-track {
					background: #f7f0ff;
				}
				&::-webkit-scrollbar-thumb {
					background: lightgray;
					/* background: #d9d9d9; */
				}
			}
		}
	}
`,u=i.default.div`
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 0 0 20px 0;
	margin-bottom: 16px;
	max-width: 1440px;
	margin: 0 auto;

	@media ( max-width: 1200px ) {
		max-width: 100%;
		padding: 0 0 18px 0;
	}

	@media ( max-width: 900px ) {
		padding: 0 0 16px 0;
		margin-bottom: 12px;
	}

	@media ( max-width: 600px ) {
		padding: 0 0 12px 0;
		margin-bottom: 10px;
	}
`,g=i.default.h2`
	font-size: 18px;
	font-weight: 600;
	color: #334155;
	margin: 0;
`,v=i.default.div`
	display: flex;
	gap: 8px;
	align-items: center;
`,f=i.default.button`
	display: flex;
	align-items: center;
	justify-content: center;
	width: 32px;
	height: 32px;
	border: 1px solid #d9d9d9;
	background: #fff;
	border-radius: 4px;
	cursor: pointer;
	transition: all 0.2s ease;
	color: #64748b;
	padding: 0;

	&:hover {
		border-color: #6b2ee5;
		color: #6b2ee5;
		background: #f5f0ff;
	}

	&:active {
		transform: scale( 0.95 );
	}

	svg {
		width: 16px;
		height: 16px;
	}
`,h=i.default.div`
	border-radius: 4px;
	display: flex;
	flex-direction: column;
	gap: 4px;
	width: 100%;

	.etn-render-cell-item {
		background: #f0f0f0;
		padding: 4px 2px;
		border-radius: 4px;
		margin-bottom: 4px;
		.etn-render-cell-item-title {
			font-size: 14px;
			font-weight: 500;
			color: #202223;
			margin: 0;
			text-transform: capitalize;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			max-width: 86px;
			min-width: 60px;
			width: 100%;
		}
		.etn-render-cell-item-time {
			font-size: 12px;
			font-weight: 400;
			color: #6d6d6d;
			margin: 0;
			white-space: nowrap;
		}
	}
`,x=i.default.h4`
	font-size: 14px;
	font-weight: 500;
	color: #202223;
	margin: 0;
`,E=i.default.p`
	font-size: 14px;
	font-weight: 400;
	color: #6d6d6d;
	margin: 0;
`,b=(0,i.default)(a.Ay)`
	background: #f7f7f7;
`,_=(0,i.default)(l)`
	height: 36px;
	border-radius: 4px;
`,y=i.default.span`
	&.recurring-badge {
		background-color: #e6f4ff;
		color: #0958d9;
		padding: 2px 8px;
		border-radius: 50px;
		font-size: 12px;
		font-weight: 500;
		margin-inline: 10px;
		display: flex;
		gap: 4px;
		cursor: pointer;
		margin-left: 10px;
	}
`},709977(e,t,n){n.r(t),n.d(t,{default:()=>x});var a=n(951609),i=n(929491),r=n(547143),o=(n(527723),n(986087)),l=n(247767),s=n(105028),c=n(792805),d=n(785666),p=n(397455),m=n(959720),u=n(452034),g=n(740728),v=n(671322);const f=(0,r.withDispatch)((e,t,{select:n})=>{const a=e(s.EF);return{invalidateEventRecurringList:()=>{const{recurringParentId:e}=n(s.EF).getEventState();a.invalidateResolution("getEventRecurringList",[e])}}}),h=(0,r.withSelect)(e=>{const t=e(s.EF),{recurringParentId:n}=t.getEventState();return{eventRecurringList:t.getEventRecurringList(n),hasResolved:t.hasFinishedResolution("getEventRecurringList",[n])}}),x=(0,i.compose)([f,h])(function(e){const{invalidateEventRecurringList:t,eventRecurringList:n,hasResolved:i}=e,{recurring_events:f}=n||{},{id:h}=(0,l.useParams)(),{selectedRecurringEvents:x,recurringParentId:E,eventRecurringParams:b}=(0,r.useSelect)(e=>e(s.EF).getEventState()),{setEventState:_}=(0,r.useDispatch)(s.EF);(0,o.useEffect)(()=>{h&&Number(h)!==E&&_({recurringParentId:Number(h)})},[h]),(0,o.useEffect)(()=>{E&&t()},[E]);const y={selectedRowKeys:x,onChange:e=>{_({selectedRecurringEvents:e})}};return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(c.A,null),(0,a.createElement)(p.ff,{className:"event-list-wrapper"},(0,a.createElement)("div",{className:"event-list-wrapper"},(0,a.createElement)(m.A,{invalidateEventList:t,selectedEventsKey:"selectedRecurringEvents",paramsKey:"eventRecurringParams",loadingKey:"eventRecurringActionLoading"}),(0,a.createElement)(d.A,{loading:!i,columns:u.Y,showPagination:!1,dataSource:f?.items||[],scroll:{x:1e3},total:f?.total_items||0,rowSelection:y,tableHeaderData:(0,a.createElement)(v.A,{data:n,id:h})}),(0,a.createElement)(g.A,{total:f?.total_items||0,currentPage:b?.paged||1,pageSize:b?.per_page||10,onPageChange:e=>{_({eventRecurringParams:{...b,paged:e}}),t()},onPageSizeChange:e=>{_({eventRecurringParams:{...b,per_page:parseInt(e),paged:1}}),t()}}))))})},671322(e,t,n){n.d(t,{A:()=>g});var a=n(951609),i=n(769815),r=n(484976),o=n(527723),l=n(327154);const s=i.default.div`
	padding: 30px 40px 10px 40px;
`,c=i.default.div`
	display: flex;
	justify-content: space-between;
	gap: 20px;
	align-items: center;

	@media ( max-width: 768px ) {
		flex-direction: column;
		align-items: flex-start;
		gap: 16px;
	}
`,d=i.default.div`
	display: flex;
	flex-direction: column;
	gap: 4px;
`,p=i.default.span`
	font-size: 14px;
	font-weight: 400;
	color: #6d6d6d;
`,m=i.default.span`
	font-size: 14px;
	font-weight: 500;
	color: #202223;
	text-transform: capitalize;
	&.etn-parent-title-link {
		a {
			max-width: 250px;
			display: inline-block;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
			color: #202223;
			text-decoration: none;
			&:hover {
				color: ${l.PRIMARY_COLOR};
				text-decoration: underline;
			}
		}
	}
`,u=(0,i.default)(m)`
	@media ( max-width: 768px ) {
		text-align: left;
	}
`,g=function(e){const{data:t,id:n}=e,{title:i,event_recurrence:l,recurring_events:g}=t||{},v=[{value:"none",label:(0,o.__)("Does not repeat","eventin")},{value:"day",label:(0,o.__)("Daily","eventin")},{value:"week",label:(0,o.__)("Weekly","eventin")},{value:"month",label:(0,o.__)("Monthly","eventin")},{value:"month-advanced",label:(0,o.__)("Monthly Advanced","eventin")},{value:"year",label:(0,o.__)("Yearly","eventin")},{value:"custom",label:(0,o.__)("Custom","eventin")}],f=l?.recurrence_freq,h=v.find(e=>e.value===f)?.label||"",x=g?.total_items||0,E=g?.total_upcomming_events||0,b=1===E?(0,o.__)("upcoming event","eventin"):(0,o.__)("upcoming events","eventin");return(0,a.createElement)(s,null,(0,a.createElement)(c,null,(0,a.createElement)(d,null,(0,a.createElement)(p,null,(0,o.__)("Event","eventin")),(0,a.createElement)(m,{className:"etn-parent-title-link"},(0,a.createElement)(r.Link,{to:`/events/edit/${n}/basic`},i||""))),(0,a.createElement)(d,null,(0,a.createElement)(p,null,(0,o.__)("Recurrence Pattern","eventin")),(0,a.createElement)(m,null,h)),(0,a.createElement)(d,null,(0,a.createElement)(p,null,(0,o.__)("Occurrences","eventin")),(0,a.createElement)(u,null,E," ",b," (",x," ",(0,o.__)("total","eventin"),")"))))}},792805(e,t,n){n.d(t,{A:()=>u});var a=n(951609),i=n(811721),r=n(892911),o=n(247767),l=n(956427),s=n(527723),c=n(607638),d=n(327154),p=n(818062),m=n(354725);const u=function(){const e=(0,o.useNavigate)(),{pathname:t}=(0,o.useLocation)(),n=["/events"!==t&&{key:"0",label:(0,s.__)("Event List","eventin"),icon:(0,a.createElement)(m.EventListIcon,{width:20,height:20}),onClick:()=>{e("/events")}},"/events/categories"!==t&&{key:"1",label:(0,s.__)("Event Categories","eventin"),icon:(0,a.createElement)(m.CategoriesIcon,null),onClick:()=>{e("/events/categories")}},"/events/tags"!==t&&{key:"2",label:(0,s.__)("Event Tags","eventin"),icon:(0,a.createElement)(m.TagIcon,null),onClick:()=>{e("/events/tags")}}];return(0,a.createElement)(l.Fill,{name:d.PRIMARY_HEADER_NAME},(0,a.createElement)(r.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,a.createElement)(r.A,{align:"center",gap:16},(0,a.createElement)(c.Ay,{variant:c.Vt,icon:(0,a.createElement)(m.AngleLeftIcon,null),sx:{height:"36px",width:"36px",backgroundColor:"#fafafa",borderColor:"transparent",lineHeight:"1"},onClick:()=>{e("/events")}}),(0,a.createElement)(p.A,{title:(0,s.__)("Recurring Events list","eventin")})),(0,a.createElement)("div",{style:{display:"flex",alignItems:"center",gap:"8px",flexWrap:"wrap"}},(0,a.createElement)(c.Ay,{variant:c.zB,htmlType:"button",onClick:()=>{e("/events/create/basic")},sx:{display:"flex",alignItems:"center"}},(0,a.createElement)(m.PlusOutlined,null),(0,s.__)("New Event","eventin")),(0,a.createElement)(r.A,{gap:12},(0,a.createElement)(i.A,{menu:{items:n},trigger:["click"],placement:"bottomRight",overlayClassName:"action-dropdown"},(0,a.createElement)(c.Ay,{variant:c.Vt,sx:{color:"#8C8C8C",height:"40px",lineHeight:"1",borderColor:"#747474",padding:"0px 10px",fontSize:"14px",fontWeight:400}},(0,a.createElement)(m.MoreIconOutlined,null)))))))}},397455(e,t,n){n.d(t,{WO:()=>r,ff:()=>i,oY:()=>o});var a=n(769815);const i=a.default.div`
	background-color: #f4f6fa;
	padding: 12px 32px;
	min-height: 100vh;

	@media ( max-width: 576px ) {
		padding: 10px 8px;
	}

	.ant-table-wrapper {
		padding: 15px 20px;
		background-color: #fff;
		border-radius: 12px;

		@media ( max-width: 576px ) {
			padding: 8px 10px;
		}
	}

	.event-list-wrapper {
		border-radius: 0 0 12px 12px;
	}

	.ant-table-thead {
		> tr {
			> th {
				background-color: #fff;
				padding-top: 10px;
				font-weight: 400;
				color: #7a7a99;
				font-size: 16px;
				&:before {
					display: none;
				}
			}
		}
	}

	tr {
		&:hover {
			background-color: #f8fafc !important;
		}
	}

	.event-title {
		color: #262626;
		font-size: 16px;
		font-weight: 600;
		line-height: 26px;
		display: inline-flex;
		margin-bottom: 6px;
	}

	.event-location,
	.event-date-time {
		color: #334155;
		font-weight: 400;
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

	.event-actions,
	.etn-table-actions {
		.ant-btn {
			padding: 0;
			width: 28px;
			height: 28px;
			line-height: 1;
			display: flex;
			justify-content: center;
			align-items: center;
			border-color: #94a3b8;
			color: #525266;
			background-color: #f5f5f5;
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
		font-size: 16px;
		color: #334155;
		padding: 0;
		text-align: left;
	}

	.author {
		font-size: 16px;
		color: #334155;
		text-transform: capitalize;
	}
	.etn-table-text {
		font-size: 14px;
		color: #202223;
		font-weight: 400;
		text-transform: capitalize;
	}
	.recurring-badge {
		background-color: #e9edf1;
		color: #1890ff;
		font-size: 12px;
		padding: 5px 12px;
		border-radius: 50px;
		font-weight: 600;
		margin-inline: 10px;
	}
`,r=a.default.button`
	display: flex;
	align-items: center;
	height: 40px;
	gap: 8px;
	padding: 8px 16px;
	background: #f9f5ff;
	border: none;
	border-radius: 6px;
	cursor: pointer;
	position: relative;
	transition: all 0.2s ease;
	svg {
		color: #ff69b4;
	}
`,o=a.default.span`
	background: linear-gradient(
		90deg,
		#fc8327 0%,
		#e83aa5 50.5%,
		#3a4ff2 100%
	);
	-webkit-background-clip: text;
	-webkit-text-fill-color: rgba( 0, 0, 0, 0 );
	background-clip: text;
`}}]);