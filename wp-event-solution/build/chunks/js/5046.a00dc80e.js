"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[5046],{11883(e,t,n){var a=n(51609),i=n(6836);n.d(t,["A",0,({height:e=22,width:t=22})=>(0,i.EZ)(()=>(({height:e,width:t})=>(0,a.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",fill:"none",viewBox:"0 0 20 20"},(0,a.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeLinejoin:"round",strokeWidth:"1.5",d:"M6.25 4.121h7.083c.69 0 1.25.56 1.25 1.25v1.25M12.5 10.788h-5M10 14.121H7.5"}),(0,a.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeWidth:"1.5",d:"M15.414 1.667H5.256c-.414 0-.837.06-1.172.306-1.062.78-1.88 2.517-.228 4.086.464.44 1.112.6 1.75.6h9.63c.662 0 1.847.095 1.847 2.114v6.211a3.34 3.34 0 0 1-3.33 3.35H6.226c-1.836 0-3.172-1.298-3.277-3.274L2.922 4.304"})))({height:e,width:t}))])},57584(e,t,n){n(51609),n(6836)},8358(e,t,n){n.d(t,{A:()=>c});var a=n(51609),i=n(27723),o=n(54725),l=n(58950),r=n(22451),s=n(27154);function c({children:e,...t}){return(0,a.createElement)(r.u,{type:l.rd.type,size:l.rd.size,variantstyle:l.rd.style,onClick:()=>window.open(s.aC,"_blank"),...t},(0,a.createElement)(o.tD6,null),e||(0,i.__)("Get Eventin Pro","eventin"))}},17026(e,t,n){var a=n(51609),i=n(77278),o=n(16370),l=n(47152),r=n(75063);n.d(t,["A",0,()=>(0,a.createElement)(l.A,{gutter:[16,16]},(0,a.createElement)(o.A,{xs:24,sm:24},(0,a.createElement)(r.A.Input,{active:!0,size:"large",style:{margin:"20px 0"}})),[...Array(6)].map((e,t)=>(0,a.createElement)(o.A,{xs:24,sm:12,md:8,key:t},(0,a.createElement)(i.A,{style:{borderRadius:8}},(0,a.createElement)(r.A.Avatar,{active:!0,size:"large",shape:"circle",style:{marginBottom:16,marginRight:16}}),(0,a.createElement)(r.A.Input,{style:{width:200,marginBottom:8},active:!0}),(0,a.createElement)(r.A.Input,{style:{width:120,marginBottom:8},active:!0}),(0,a.createElement)("div",{style:{display:"flex",gap:10,alignItems:"center",marginTop:16}},(0,a.createElement)(r.A.Button,{style:{width:100},active:!0}),(0,a.createElement)(r.A.Button,{style:{width:100},active:!0}))))))])},19575(e,t,n){var a=n(52619),i=n(27723),o=n(64282);n.d(t,["A",0,async(e,t,n={})=>{try{const i=await o.A.extensions.updateExtension({name:e,status:t,...n});return(0,a.doAction)("eventin_notification",{type:"success",message:i?.message}),!0}catch(e){return(0,a.doAction)("eventin_notification",{type:"error",message:e?.message||(0,i.__)("Update failed! Please check the plugin list and try again.","eventin")}),!1}}])},49603(e,t,n){var a=n(51609),i=n(47143),o=n(59255),l=n(85890),r=n(4629),s=n(44207),c=n(49456),d=n(10229),g=n(16947),m=n(15875),u=n(1907);n.d(t,["A",0,()=>{const{modalType:e}=(0,i.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:t}=(0,i.useDispatch)("eventin/global"),n=()=>t({modalType:null,modalProps:null});return e&&{[o.ew.ZOOM_CONFIG]:(0,a.createElement)(u.A,{open:!0,onCancel:n}),[o.ew.GOOGLE_MEET_CONFIG]:(0,a.createElement)(s.A,{open:!0,onCancel:n}),[o.ew.EVENTIN_AI_CONFIG]:(0,a.createElement)(l.A,{open:!0,onCancel:n}),[o.ew.GOOGLE_MAP_CONFIG]:(0,a.createElement)(r.A,{open:!0,onCancel:n}),[o.ew.TUTOR_LMS_CONFIG]:(0,a.createElement)(m.A,{open:!0,onCancel:n}),[o.ew.LEARNDASH_CONFIG]:(0,a.createElement)(c.A,{open:!0,onCancel:n}),[o.ew.STRIPE_CONFIG]:(0,a.createElement)(g.A,{open:!0,onCancel:n}),[o.ew.PAYPAL_CONFIG]:(0,a.createElement)(d.A,{open:!0,onCancel:n})}[e]||null}])},22423(e,t,n){var a=n(51609),i=n(29491),o=n(47143),l=n(86087),r=n(27723),s=n(16370),c=n(92911),d=n(47152),g=n(67313),m=n(6660);const{Title:u,Text:p}=g.A,_=(0,o.withDispatch)((e,t,{select:n})=>{const a=e("eventin/global");return{invalidateExtensions:()=>a.invalidateResolution("getExtensions"),invalidateSettings:()=>{a.invalidateResolution("getSettings"),n("eventin/global").getSettings()}}}),v=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),f=(0,i.compose)(v,_)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:i,invalidateSettings:o}=e||{},[g,_]=(0,l.useState)([]);return(0,l.useEffect)(()=>{t&&_(Array.isArray(t)&&t?.filter(e=>"addon"===e.type)||[])},[t]),(0,a.createElement)("div",{className:"etn-module-section"},(0,a.createElement)(d.A,{gutter:[30,30]},(0,a.createElement)(s.A,{span:24},(0,a.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,a.createElement)(u,{level:3,className:"etn-extension-title"},(0,r.__)("Addons","eventin")),(0,a.createElement)(p,{className:"etn-extension-description"}," ",(0,r.__)("Eventin addons","eventin")))),g.map((e,t)=>(0,a.createElement)(s.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(m.A,{module:e,index:t,invalidateExtensions:i,isExtensionsLoading:n,invalidateSettings:o})))))});n.d(t,["A",0,f])},85890(e,t,n){var a=n(51609),i=n(27723),o=n(29491),l=n(47143),r=n(52619),s=n(60742),c=n(75093),d=n(64282);const g=(0,l.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),m=(0,o.compose)(g)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[g]=s.A.useForm(),{integrationLoading:m}=(0,l.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:u,invalidateResolution:p}=(0,l.useDispatch)("eventin/global"),_=Array.isArray(o)&&o?.find(e=>"eventin_ai"===e.slug),{data:v={}}=_||{data:{}},{eventin_ai_auth_key:f}=v||{},h=async()=>{try{const e=g.getFieldsValue();u({integrationLoading:!0}),(await d.A.settings.updateSettings(e)).eventin_ai_auth_key&&(0,r.doAction)("eventin_notification",{type:"success",message:(0,i.__)("Open AI key updated successfully","eventin")}),p("getExtensions"),n()}catch(e){(0,r.doAction)("eventin_notification",{type:"error",message:e.message})}finally{u({integrationLoading:!1,modalType:null})}};return(0,a.createElement)(c.xK,{open:t,onCancel:n,title:(0,i.__)("Eventin AI Configure","eventin"),onConnect:h,width:500,loading:m,form:g},(0,a.createElement)(s.A,{form:g,layout:"vertical",onFinish:h,initialValues:{eventin_ai_auth_key:f}},(0,a.createElement)(c.h5,{label:(0,i.__)("Open AI Key","eventin"),name:"eventin_ai_auth_key",placeholder:(0,i.__)("Enter Open AI Key","eventin"),required:!0,type:"password",rules:[{required:!0,message:(0,i.__)("Open AI Key is required","eventin")}]})))});n.d(t,["A",0,m])},24581(e,t,n){n.d(t,{A:()=>s});var a=n(51609),i=n(56427),o=n(92911),l=n(18062),r=n(27154);function s(e){const{title:t}=e;return(0,a.createElement)(i.Fill,{name:r.PQ},(0,a.createElement)(o.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,a.createElement)(l.A,{title:t})))}},4436(e,t,n){var a=n(51609),i=n(86087),o=n(11804),l=n(17026),r=n(59255),s=n(98739);const c=r.ld.map(e=>({value:e.key,label:(0,a.createElement)("span",{className:"etn-segment-label"},e.icon,e.label)}));n.d(t,["A",0,function(e){const{activeTab:t,setActiveTab:n,extensions:d}=e||{},[g,m]=(0,i.useState)(!0);if((0,i.useEffect)(()=>{null!=d&&m(!1)},[d]),g)return(0,a.createElement)(l.A,null);const u=r.ld.find(e=>e.key===t)||r.ld[0];return(0,a.createElement)("div",{className:"etn-extensions-container"},(0,a.createElement)(s.GP,null,(0,a.createElement)(o.A,{value:t,onChange:e=>n(e),options:c,className:"etn-segment-nav"})),(0,a.createElement)(s.nA,null,(0,a.createElement)(s.JS,{key:t},u.children)))}])},4629(e,t,n){var a=n(51609),i=n(27723),o=n(29491),l=n(47143),r=n(52619),s=n(60742),c=n(75093),d=n(64282);const g=(0,l.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),m=(0,o.compose)(g)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[g]=s.A.useForm(),{integrationLoading:m}=(0,l.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:u,invalidateResolution:p}=(0,l.useDispatch)("eventin/global"),_=Array.isArray(o)&&o?.find(e=>"google_map"===e.slug),{data:v={}}=_||{data:{}},{google_api_key:f}=v||{},h=async()=>{try{const e=g.getFieldsValue();u({integrationLoading:!0}),(await d.A.settings.updateSettings(e)).google_api_key&&(0,r.doAction)("eventin_notification",{type:"success",message:(0,i.__)("Google Map API key updated successfully","eventin")}),p("getExtensions"),n()}catch(e){(0,r.doAction)("eventin_notification",{type:"error",message:e.message})}finally{u({integrationLoading:!1})}};return(0,a.createElement)(c.xK,{open:t,onCancel:n,title:(0,i.__)("Google Map Configure","eventin"),onConnect:h,width:500,loading:m,form:g},(0,a.createElement)(s.A,{form:g,layout:"vertical",onFinish:h,initialValues:{google_api_key:f}},(0,a.createElement)(c.h5,{label:(0,i.__)("Map API Key","eventin"),name:"google_api_key",placeholder:(0,i.__)("Enter Map API Key","eventin"),tooltip:(0,i.__)("Map API Key","eventin"),required:!0,rules:[{required:!0,message:(0,i.__)("Map API Key is required","eventin")}]})))});n.d(t,["A",0,m])},44207(e,t,n){var a=n(51609),i=n(47143),o=n(29491),l=n(52619),r=n(27723),s=n(60742),c=n(75093),d=n(64282),g=n(54725);const m=(0,i.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),u=(0,o.compose)(m)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[m]=s.A.useForm(),{integrationLoading:u}=(0,i.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:p,invalidateResolution:_}=(0,i.useDispatch)("eventin/global"),v=Array.isArray(o)&&o?.find(e=>"google_meet"===e.slug),{data:f={}}=v||{data:{}},{google_meet_client_id:h,google_meet_client_secret_key:x,google_meet_redirect_url:E}=f||{},y=async()=>{try{const e=m.getFieldsValue();p({integrationLoading:!0});const t=await d.A.settings.updateSettings(e);_("getExtensions"),t.google_meet_authorize_url&&(window.location.href=t.google_meet_authorize_url)}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e.message})}finally{p({integrationLoading:!1})}};return(0,a.createElement)(c.xK,{open:t,onCancel:n,title:(0,r.__)("Google Meet Configure","eventin"),onConnect:y,width:500,loading:u,form:m},(0,a.createElement)(s.A,{form:m,layout:"vertical",onFinish:y,initialValues:{google_meet_client_id:h,google_meet_client_secret_key:x,google_meet_redirect_url:E}},(0,a.createElement)(c.h5,{label:(0,r.__)("Client ID","eventin"),name:"google_meet_client_id",placeholder:(0,r.__)("Enter Client ID","eventin"),tooltip:(0,r.__)("Enter Client ID","eventin"),required:!0,type:"password",rules:[{required:!0,message:(0,r.__)("Client ID is required","eventin")}]}),(0,a.createElement)(c.h5,{label:(0,r.__)("Client Secret Key","eventin"),name:"google_meet_client_secret_key",placeholder:(0,r.__)("Enter Client Secret Key","eventin"),tooltip:(0,r.__)("Enter Client Secret Key","eventin"),required:!0,type:"password",rules:[{required:!0,message:(0,r.__)("Client Secret Key is required","eventin")}]}),(0,a.createElement)(s.A.Item,{label:(0,r.__)("Authorized Redirect URL","eventin"),name:"google_meet_redirect_url"},(0,a.createElement)(c.I3,{copyText:E,buttonTooltipText:(0,r.__)("Copy Redirect URL","eventin"),icon:(0,a.createElement)(g.d1Z,null),placeholder:(0,r.__)("Enter redirect url","eventin")}))))});n.d(t,["A",0,u])},20710(e,t,n){var a=n(51609),i=n(29491),o=n(47143),l=n(86087),r=n(27723),s=n(16370),c=n(92911),d=n(47152),g=n(67313),m=n(6660);const{Title:u,Text:p}=g.A,_=(0,o.withDispatch)(e=>{const t=e("eventin/global");return{invalidateExtensions:()=>t.invalidateResolution("getExtensions")}}),v=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),f=(0,i.compose)(v,_)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:i}=e||{},[o,g]=(0,l.useState)([]);return(0,l.useEffect)(()=>{t&&g(Array.isArray(t)&&t?.filter(e=>"integration"===e.type)||[])},[t]),(0,a.createElement)("div",{className:"etn-module-section"},(0,a.createElement)(d.A,{gutter:[30,30]},(0,a.createElement)(s.A,{span:24},(0,a.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,a.createElement)(u,{level:3,className:"etn-extension-title"},(0,r.__)("Integrations","eventin")),(0,a.createElement)(p,{className:"etn-extension-description"}," ",(0,r.__)("Third-party integrations","eventin")))),o.map((e,t)=>(0,a.createElement)(s.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(m.A,{module:e,index:t,invalidateExtensions:i,isExtensionsLoading:n})))))});n.d(t,["A",0,f])},49456(e,t,n){var a=n(51609),i=n(47143),o=n(86087),l=n(27723),r=n(52619),s=n(69815),c=n(38181),d=n(52741),g=n(92911),m=n(60742),u=n(51643),p=n(75093),_=n(7638),v=n(64282);const f=s.A.div`
	.ant-form-item-label {
		overflow: visible;
		white-space: normal;
		text-align: left;
	}
	.ant-form-item-label > label {
		height: auto;
		min-height: 32px;
		white-space: normal;
		line-height: 1.4;
		align-items: flex-start;
		padding-top: 4px;
	}
	.ant-form-item-label > label::after {
		display: none;
	}
	.etn-checkbox-label {
		white-space: nowrap;
	}
`,h={enroll_mode:"all_attendees",auto_create_users:!0,send_credentials:!0,unenroll_on_refund:!0},x=e=>"on"===e||!0===e;n.d(t,["A",0,({open:e,onCancel:t})=>{const[n]=m.A.useForm(),[s,E]=(0,o.useState)(h),[y,b]=(0,o.useState)(!1),[A,w]=(0,o.useState)(!0),{integrationLoading:k}=(0,i.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:C}=(0,i.useDispatch)("eventin/global");(0,o.useEffect)(()=>{let e=!0;return(async()=>{try{const t=await v.A.learnDash.getSettings();if(!e)return;const a={enroll_mode:t?.enroll_mode||h.enroll_mode,auto_create_users:x(t?.auto_create_users),send_credentials:x(t?.send_credentials),unenroll_on_refund:x(t?.unenroll_on_refund)};E(a),n.setFieldsValue(a)}catch(e){(0,r.doAction)("eventin_notification",{type:"error",message:e?.message||(0,l.__)("Failed to load settings","eventin")})}finally{e&&w(!1)}})(),()=>{e=!1}},[n]);const S=async()=>{try{const e=await n.validateFields();b(!0),C({integrationLoading:!0});const a={enroll_mode:e.enroll_mode,auto_create_users:e.auto_create_users?"on":"off",send_credentials:e.send_credentials?"on":"off",unenroll_on_refund:e.unenroll_on_refund?"on":"off"};await v.A.learnDash.updateSettings(a),(0,r.doAction)("eventin_notification",{type:"success",message:(0,l.__)("LearnDash settings saved","eventin")}),t()}catch(e){if(e?.errorFields)return;(0,r.doAction)("eventin_notification",{type:"error",message:e?.message||(0,l.__)("Failed to save settings","eventin")})}finally{b(!1),C({integrationLoading:!1})}},L=(0,a.createElement)(g.A,{align:"center",justify:"flex-end",gap:10},(0,a.createElement)(_.Ay,{variant:_.zB,onClick:S,loading:y||k,type:"submit"},(0,l.__)("Save Changes","eventin")));return(0,a.createElement)(p.xK,{open:e,onCancel:t,title:(0,l.__)("LearnDash Integration for Eventin","eventin"),width:580,loading:y||A,footerContent:L,form:n},(0,a.createElement)(f,null,(0,a.createElement)(m.A,{form:n,layout:"horizontal",labelCol:{span:14},wrapperCol:{span:10},labelAlign:"left",colon:!1,onFinish:S,initialValues:s},(0,a.createElement)(m.A.Item,{label:(0,l.__)("Who gets enrolled?","eventin"),name:"enroll_mode",layout:"vertical",labelCol:{span:24},wrapperCol:{span:24}},(0,a.createElement)(u.Ay.Group,null,(0,a.createElement)(g.A,{vertical:!0,gap:8},(0,a.createElement)(u.Ay,{value:"purchaser"},(0,l.__)("Purchaser only","eventin")),(0,a.createElement)(u.Ay,{value:"all_attendees"},(0,l.__)("Every registered attendee","eventin"))))),(0,a.createElement)(d.A,{style:{marginBlock:"5px"}}),(0,a.createElement)(m.A.Item,{name:"auto_create_users",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,l.__)("Create WP accounts for guest attendees","eventin"))),(0,a.createElement)(m.A.Item,{name:"send_credentials",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,l.__)("Email login credentials to new users","eventin"))),(0,a.createElement)(m.A.Item,{name:"unenroll_on_refund",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,l.__)("Revoke course access on refund / cancellation / failure","eventin"))))))}])},64945(e,t,n){n.d(t,{A:()=>x});var a=n(51609),i=n(29491),o=n(47143),l=n(86087),r=n(27723),s=n(16370),c=n(92911),d=n(47152),g=n(67313),m=n(6660),u=n(59255),p=n(98739);const{Title:_,Text:v}=g.A,f=(0,o.withDispatch)((e,t,{select:n})=>{const a=e("eventin/global");return{invalidateExtensions:()=>a.invalidateResolution("getExtensions"),invalidateSettings:()=>{a.invalidateResolution("getSettings"),n("eventin/global").getSettings()}}}),h=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),x=(0,i.compose)(h,f)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:i,invalidateSettings:o}=e||{},[g,f]=(0,l.useState)([]),[h,x]=(0,l.useState)([]),[E,y]=(0,l.useState)([]),[b,A]=(0,l.useState)([]);return(0,l.useEffect)(()=>{if(!t)return;const e=Object.values(t),n=u.U9.map(t=>e.find(e=>e.slug===t||e.name===t)).filter(Boolean),a=new Set(n.flatMap(e=>[e.slug,e.name]).filter(Boolean)),i=t=>e.filter(e=>e.type===t&&!(e=>a.has(e.slug)||a.has(e.name))(e));A(n),f(i("module")),x(i("addon")),y(i("integration"))},[t]),(0,a.createElement)("div",{className:"etn-module-section"},b.length>0&&(0,a.createElement)(p.i7,null,(0,a.createElement)("div",{className:"etn-featured-heading"},(0,a.createElement)("span",{className:"etn-featured-ribbon"},(0,r.__)("Featured","eventin"))),(0,a.createElement)(d.A,{gutter:[30,30]},b.map((e,t)=>(0,a.createElement)(s.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(m.A,{module:e,index:t,invalidateExtensions:i,isExtensionsLoading:n,invalidateSettings:o}))))),(0,a.createElement)(d.A,{gutter:[30,30]},(0,a.createElement)(s.A,{span:24},(0,a.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,a.createElement)(_,{level:3,className:"etn-extension-title"},(0,r.__)("Modules","eventin")),(0,a.createElement)(v,{className:"etn-extension-description"}," ",(0,r.__)("Eventin modules","eventin")))),g.map((e,t)=>(0,a.createElement)(s.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(m.A,{module:e,index:t,invalidateExtensions:i,isExtensionsLoading:n})))),(0,a.createElement)(d.A,{gutter:[30,30]},(0,a.createElement)(s.A,{span:24},(0,a.createElement)(c.A,{justify:"space-between",align:"center",gap:10,style:{marginTop:"30px"}},(0,a.createElement)(_,{level:3,className:"etn-extension-title"},(0,r.__)("Addons","eventin")),(0,a.createElement)(v,{className:"etn-extension-description"}," ",(0,r.__)("Eventin addons","eventin")))),h.map((e,t)=>(0,a.createElement)(s.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(m.A,{module:e,index:t,invalidateExtensions:i,isExtensionsLoading:n,invalidateSettings:o})))),(0,a.createElement)(d.A,{gutter:[30,30]},(0,a.createElement)(s.A,{span:24},(0,a.createElement)(c.A,{justify:"space-between",align:"center",gap:10,style:{marginTop:"30px"}},(0,a.createElement)(_,{level:3,className:"etn-extension-title"},(0,r.__)("Integrations","eventin")),(0,a.createElement)(v,{className:"etn-extension-description"}," ",(0,r.__)("Eventin integrations","eventin")))),E.map((e,t)=>(0,a.createElement)(s.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(m.A,{module:e,index:t,invalidateExtensions:i,isExtensionsLoading:n})))))})},10229(e,t,n){var a=n(51609),i=n(47143),o=n(29491),l=n(52619),r=n(27723),s=n(38181),c=n(60742),d=n(75093),g=n(64282);const m=(0,i.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),u=(0,o.compose)(m)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[m]=c.A.useForm(),{integrationLoading:u}=(0,i.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:p,invalidateResolution:_}=(0,i.useDispatch)("eventin/global"),v=Array.isArray(o)&&o?.find(e=>"paypal"===e.slug),{data:f={}}=v||{data:{}},{paypal_client_id:h,paypal_client_secret:x,paypal_sandbox:E}=f||{},y=async()=>{try{const e=m.getFieldsValue();p({integrationLoading:!0}),await g.A.settings.updateSettings(e),_("getExtensions"),(0,l.doAction)("eventin_notification",{type:"success",message:(0,r.__)("PayPal settings saved","eventin")}),n?.()}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e.message})}finally{p({integrationLoading:!1})}};return(0,a.createElement)(d.xK,{open:t,onCancel:n,title:(0,r.__)("PayPal Configure","eventin"),onConnect:y,width:500,loading:u,form:m},(0,a.createElement)(c.A,{form:m,layout:"vertical",onFinish:y,initialValues:{paypal_client_id:h,paypal_client_secret:x,paypal_sandbox:E}},(0,a.createElement)(d.h5,{label:(0,r.__)("PayPal Client ID","eventin"),name:"paypal_client_id",placeholder:(0,r.__)("Enter client id","eventin"),required:!0,rules:[{required:!0,message:(0,r.__)("Client ID is required","eventin")}]}),(0,a.createElement)(d.h5,{label:(0,r.__)("PayPal Secret Key","eventin"),name:"paypal_client_secret",placeholder:(0,r.__)("Enter secret key","eventin"),required:!0,rules:[{required:!0,message:(0,r.__)("Secret key is required","eventin")}]}),(0,a.createElement)(c.A.Item,{name:"paypal_sandbox",valuePropName:"checked"},(0,a.createElement)(s.A,null,(0,r.__)("Test payments with PayPal sandbox","eventin")))))});n.d(t,["A",0,u])},37762(e,t,n){var a=n(51609),i=n(27723),o=n(67313),l=n(11883);const{Text:r}=o.A,s=()=>(0,a.createElement)("svg",{width:"16",height:"16",viewBox:"0 0 16 16",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,a.createElement)("circle",{cx:"8",cy:"8",r:"7",stroke:"currentColor",strokeWidth:"1.5"}),(0,a.createElement)("path",{d:"M6.5 5.5L11 8L6.5 10.5V5.5Z",fill:"currentColor"})),c=({href:e,children:t})=>(0,a.createElement)("a",{href:e,target:"_blank",rel:"noreferrer",className:"etn-doc-link-item"},t);n.d(t,["v",0,({description:e,notice:t,doc_link:n,video_link:o})=>(0,a.createElement)("div",{className:"etn-card-desc"},(0,a.createElement)(r,null,e.length>90?e.slice(0,90).concat("..."):e),t&&(0,a.createElement)(r,{style:{display:"flex",color:"#ff7129",marginTop:"10px"}},t),(0,a.createElement)("div",{className:"etn-doc-links"},(0,a.createElement)(c,{href:n},(0,a.createElement)(l.A,null)," ",(0,i.__)("Learn More","eventin")),o&&(0,a.createElement)(c,{href:o},(0,a.createElement)(s,null)," ",(0,i.__)("Tutorial","eventin"))))])},32066(e,t,n){var a=n(51609),i=n(86087),o=n(27723),l=n(47143),r=n(92911),s=n(7638),c=n(64282),d=n(52619),g=n(8358),m=n(77908);const u=[m.e.TUTOR_LMS_CONFIG,m.e.LEARNDASH_CONFIG],p=new Set(Object.values(m.e)),_={fontSize:"14px",fontWeight:500,height:"40px",padding:"0 22px",borderRadius:"8px"},v="zoom";n.d(t,["x",0,e=>{const{type:t,isProActive:n,is_pro:m,deps:f,loading:h,data:x}=e,{setEventinState:E}=(0,l.useDispatch)("eventin/global"),{actions:y,btnStyle:b}=(({status:e,type:t,slug:n,onChangeStatus:a,upgrade_link:l,settings_link:r,deps:c,upgrade:d,setEventinState:g})=>{const m=(0,i.useCallback)(()=>{g({modalType:n})},[n,g]);if("integration"===t&&c?.length>0)return"off"===e?{actions:[],btnStyle:_}:{actions:{install:[{label:(0,o.__)("Activate","eventin"),variant:s.zB,onClick:()=>a("activate")}],activate:[{label:(0,o.__)("Deactivate","eventin"),variant:s.Rm,onClick:()=>a("deactivate")}]}[e]||[{label:(0,o.__)("Install","eventin"),variant:s.zB,onClick:()=>a("install")}],btnStyle:_};const v={on:()=>"integration"===t||"eventin_ai"===n?p.has(n)?[{label:(0,o.__)("Configure","eventin"),variant:s.Rm,onClick:m}]:[]:c?.length||"addon"===t?[{label:(0,o.__)("Install","eventin"),variant:s.zB,onClick:()=>a("install")}]:[],install:()=>[{label:(0,o.__)("Activate","eventin"),variant:s.zB,onClick:()=>a("activate")}],upgrade:()=>[{label:(0,o.__)("Download","eventin"),variant:s.zB,href:l,target:"_blank"}],activate:()=>{const e=u.includes(n)?{label:(0,o.__)("Configure","eventin"),variant:s.Rm,onClick:m}:r&&{label:(0,o.__)("Configure","eventin"),variant:s.Rm,href:r,target:"_blank"};return[{label:(0,o.__)("Deactivate","eventin"),variant:s.Rm,onClick:()=>a("deactivate")},e].filter(Boolean)}};return{actions:v[e]?.()||[],btnStyle:_}})({...e,setEventinState:E}),[A,w]=(0,i.useState)(!1),[k,C]=(0,i.useState)({zoom_connected:"yes"===x?.zoom_connected,google_meet_connected:"yes"===x?.google_meet_connected}),S=(0,i.useCallback)(async e=>{try{w(!0),await c.A.settings.updateSettings(e)&&(C(t=>({...t,zoom_connected:e?.zoom_connected,google_meet_connected:e?.google_meet_connected})),(0,d.doAction)("eventin_notification",{type:"success",message:(0,o.__)("Disconnected successfully","eventin")}))}catch(e){(0,d.doAction)("eventin_notification",{type:"error",message:e.message})}finally{w(!1)}},[]),L=(0,i.useCallback)((e,n,i)=>"integration"===t&&n?(0,a.createElement)(s.Ay,{style:b,variant:e===v?s.Vt:s.zB,onClick:()=>S(i),loading:A,disabled:A},(0,o.__)("Disconnect","eventin")):null,[t,b,S,A]);return(0,i.useMemo)(()=>{if(!n&&m)return(0,a.createElement)(g.A,{sx:{height:"36px",fontSize:"14px"}});if("module"===t&&!f?.length)return null;if(!y.length)return null;const e=L(v,k?.zoom_connected,{zoom_token:{},zoom_connected:!1}),i=L("google_meet",k?.google_meet_connected,{google_token:{},google_meet_connected:!1});return(0,a.createElement)(r.A,{gap:20,wrap:"wrap"},e,i,y.map(({label:e,style:t,...n},i)=>(0,a.createElement)(s.Ay,{key:i,...n,style:{...b,...t||{}},loading:h},e)))},[y,b,f,h,n,m,t,k,L])}])},70334(e,t,n){var a=n(51609),i=n(43960);n.d(t,["j",0,({checked:e,loading:t,disabled:n,onChange:o})=>(0,a.createElement)(i.A,{className:"etn-addon-module-switch",loading:t,checked:e,onChange:o,disabled:n})])},6660(e,t,n){var a=n(51609),i=n(27723),o=n(19549),l=n(67313),r=(n(57584),n(64282)),s=n(25280),c=n(98739),d=n(37762),g=n(32066),m=n(70334);const{Title:u}=l.A,p=["stripe","paypal"],_=e=>{if(null==e)return!1;if("boolean"==typeof e)return e;if("number"==typeof e)return 0!==e;if("string"==typeof e){const t=e.trim().toLowerCase();return""!==t&&"0"!==t&&"false"!==t}return!!e};n.d(t,["A",0,({module:e,invalidateExtensions:t,isExtensionsLoading:n,invalidateSettings:l,index:v=0})=>{const{name:f,title:h,description:x,status:E,notice:y,icon:b,settings_link:A,doc_link:w,video_link:k,is_pro:C,upgrade_link:S,upgrade:L,deps:I,type:N,slug:z,badge_tags:F=[],data:T={}}=e||{},R=p.includes(z)?async e=>{if(!e)return{};let t=null;try{t=await r.A.settings.getSettings()}catch(e){t=null}const n=(e=>{if(!e)return[];const t=[];return"woocommerce"===e.sell_tickets&&t.push("WooCommerce"),_(e.surecart_status)&&t.push("SureCart"),_(e.fluentcart_status)&&t.push("FluentCart"),t})(t);return 0===n.length?{}:new Promise(e=>{o.A.confirm({title:(0,i.__)("Disable other payment methods?","eventin"),content:n.join(", ")+" "+(0,i.__)("is currently enabled. Enabling this payment method will disable it. Continue?","eventin"),okText:(0,i.__)("Continue","eventin"),cancelText:(0,i.__)("Cancel","eventin"),onOk:()=>e({clear_conflicts:!0}),onCancel:()=>e(!1)})})}:void 0,{status:P,isLoading:V,buttonLoading:G,isActive:M,toggleModule:O,updateStatus:D}=(0,s.A)(f,E,t,n,l,R),q=!!window.localized_data_obj.evnetin_pro_active,j=60*Math.min(v,8)+"ms";return(0,a.createElement)(c.vi,{style:{animationDelay:j}},(0,a.createElement)("div",{className:"etn-module-card-header"},(0,a.createElement)("div",{className:"etn-module-card-header-icon",dangerouslySetInnerHTML:{__html:b}}),!(!q&&C)&&(0,a.createElement)(m.j,{checked:M,onChange:O,loading:V})),(0,a.createElement)("div",{className:"etn-module-card-body"},(0,a.createElement)("div",{style:{display:"flex",alignItems:"center",flexWrap:"wrap",gap:"8px",margin:"16px 0 10px 0"}},(0,a.createElement)(u,{level:4,className:"etn-module-card-title"},h),F.map(e=>(0,a.createElement)(c.Ij,{key:e,variant:e.toLowerCase()},e))),(0,a.createElement)(d.v,{description:x,notice:y,doc_link:w,video_link:k})),(0,a.createElement)(c.dQ,null,(0,a.createElement)(g.x,{status:E,loading:G,onChangeStatus:D,upgrade:L,upgrade_link:S,settings_link:A,type:N,slug:z,deps:I,is_pro:C,isProActive:q,data:T})))}])},16947(e,t,n){var a=n(51609),i=n(47143),o=n(29491),l=n(52619),r=n(27723),s=n(60742),c=n(75093),d=n(64282);const g=(0,i.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),m=(0,o.compose)(g)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[g]=s.A.useForm(),{integrationLoading:m}=(0,i.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:u,invalidateResolution:p}=(0,i.useDispatch)("eventin/global"),_=Array.isArray(o)&&o?.find(e=>"stripe"===e.slug),{data:v={}}=_||{data:{}},{stripe_live_publishable_key:f,stripe_live_secret_key:h,etn_stripe_webhook_secret:x}=v||{},E=async()=>{try{const e=g.getFieldsValue();u({integrationLoading:!0}),await d.A.settings.updateSettings(e),p("getExtensions"),(0,l.doAction)("eventin_notification",{type:"success",message:(0,r.__)("Stripe settings saved","eventin")}),n?.()}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e.message})}finally{u({integrationLoading:!1})}};return(0,a.createElement)(c.xK,{open:t,onCancel:n,title:(0,r.__)("Stripe Configure","eventin"),onConnect:E,width:500,loading:m,form:g},(0,a.createElement)(s.A,{form:g,layout:"vertical",onFinish:E,initialValues:{stripe_live_publishable_key:f,stripe_live_secret_key:h,etn_stripe_webhook_secret:x}},(0,a.createElement)(c.h5,{label:(0,r.__)("Publishable Key","eventin"),name:"stripe_live_publishable_key",placeholder:(0,r.__)("Enter publishable key","eventin"),required:!0,rules:[{required:!0,message:(0,r.__)("Publishable key is required","eventin")}]}),(0,a.createElement)(c.h5,{label:(0,r.__)("Secret Key","eventin"),name:"stripe_live_secret_key",placeholder:(0,r.__)("Enter secret key","eventin"),required:!0,rules:[{required:!0,message:(0,r.__)("Secret key is required","eventin")}]}),(0,a.createElement)(c.h5,{label:(0,r.__)("Webhook Signing Secret","eventin"),name:"etn_stripe_webhook_secret",placeholder:(0,r.__)("Enter webhook signing secret (whsec_...)","eventin")})))});n.d(t,["A",0,m])},15875(e,t,n){var a=n(51609),i=n(47143),o=n(86087),l=n(27723),r=n(52619),s=n(69815),c=n(38181),d=n(52741),g=n(92911),m=n(60742),u=n(51643),p=n(75093),_=n(7638),v=n(64282);const f=s.A.div`
	.ant-form-item-label {
		overflow: visible;
		white-space: normal;
		text-align: left;
	}
	.ant-form-item-label > label {
		height: auto;
		min-height: 32px;
		white-space: normal;
		line-height: 1.4;
		align-items: flex-start;
		padding-top: 4px;
	}
			.ant-form-item-label > label::after {
		display: none;
	}
	.etn-checkbox-label {
		white-space: nowrap;
	}
`,h={enroll_mode:"all_attendees",auto_create_users:!0,send_credentials:!0,unenroll_on_refund:!0},x=e=>"on"===e||!0===e;n.d(t,["A",0,({open:e,onCancel:t})=>{const[n]=m.A.useForm(),[s,E]=(0,o.useState)(h),[y,b]=(0,o.useState)(!1),[A,w]=(0,o.useState)(!0),{integrationLoading:k}=(0,i.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:C}=(0,i.useDispatch)("eventin/global");(0,o.useEffect)(()=>{let e=!0;return(async()=>{try{const t=await v.A.tutorLms.getSettings();if(!e)return;const a={enroll_mode:t?.enroll_mode||h.enroll_mode,auto_create_users:x(t?.auto_create_users),send_credentials:x(t?.send_credentials),unenroll_on_refund:x(t?.unenroll_on_refund)};E(a),n.setFieldsValue(a)}catch(e){(0,r.doAction)("eventin_notification",{type:"error",message:e?.message||(0,l.__)("Failed to load settings","eventin")})}finally{e&&w(!1)}})(),()=>{e=!1}},[n]);const S=async()=>{try{const e=await n.validateFields();b(!0),C({integrationLoading:!0});const a={enroll_mode:e.enroll_mode,auto_create_users:e.auto_create_users?"on":"off",send_credentials:e.send_credentials?"on":"off",unenroll_on_refund:e.unenroll_on_refund?"on":"off"};await v.A.tutorLms.updateSettings(a),(0,r.doAction)("eventin_notification",{type:"success",message:(0,l.__)("Tutor LMS settings saved","eventin")}),t()}catch(e){if(e?.errorFields)return;(0,r.doAction)("eventin_notification",{type:"error",message:e?.message||(0,l.__)("Failed to save settings","eventin")})}finally{b(!1),C({integrationLoading:!1})}},L=(0,a.createElement)(g.A,{align:"center",justify:"flex-end",gap:10},(0,a.createElement)(_.Ay,{variant:_.zB,onClick:S,loading:y||k,type:"submit"},(0,l.__)("Save Changes","eventin")));return(0,a.createElement)(p.xK,{open:e,onCancel:t,title:(0,l.__)("Tutor LMS Integration for Eventin","eventin"),width:580,loading:y||A,footerContent:L,form:n},(0,a.createElement)(f,null,(0,a.createElement)(m.A,{form:n,layout:"horizontal",labelCol:{span:14},wrapperCol:{span:10},labelAlign:"left",colon:!1,onFinish:S,initialValues:s},(0,a.createElement)(m.A.Item,{label:(0,l.__)("Who gets enrolled?","eventin"),name:"enroll_mode",layout:"vertical",labelCol:{span:24},wrapperCol:{span:24}},(0,a.createElement)(u.Ay.Group,null,(0,a.createElement)(g.A,{vertical:!0,gap:8},(0,a.createElement)(u.Ay,{value:"purchaser"},(0,l.__)("Purchaser only","eventin")),(0,a.createElement)(u.Ay,{value:"all_attendees"},(0,l.__)("Every registered attendee","eventin"))))),(0,a.createElement)(d.A,{style:{marginBlock:"5px"}}),(0,a.createElement)(m.A.Item,{name:"auto_create_users",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,l.__)("Create WP accounts for guest attendees","eventin"))),(0,a.createElement)(m.A.Item,{name:"send_credentials",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,l.__)("Email login credentials to new users","eventin"))),(0,a.createElement)(m.A.Item,{name:"unenroll_on_refund",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,l.__)("Unenroll on refund / cancellation / failure","eventin"))))))}])},1907(e,t,n){var a=n(51609),i=n(47143),o=n(27723),l=n(29491),r=n(52619),s=n(60742),c=n(75093),d=n(64282),g=n(54725);const m=(0,i.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),u=(0,l.compose)(m)(e=>{const{open:t,onCancel:n,extensionsList:l}=e,[m]=s.A.useForm(),{integrationLoading:u}=(0,i.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:p,invalidateResolution:_}=(0,i.useDispatch)("eventin/global"),v=Array.isArray(l)&&l?.find(e=>"zoom"===e.slug),{data:f={}}=v||{data:{}},{zoom_redirect_url:h}=f||{},x=async()=>{try{const e=m.getFieldsValue();p({integrationLoading:!0});const t=await d.A.settings.updateSettings(e);_("getExtensions"),t.zoom_authorize_url&&(window.location.href=t.zoom_authorize_url)}catch(e){(0,r.doAction)("eventin_notification",{type:"error",message:e.message})}finally{p({integrationLoading:!1})}};return(0,a.createElement)(c.xK,{open:t,onCancel:n,title:(0,o.__)("Zoom Configure","eventin"),onConnect:x,width:500,loading:u,form:m},(0,a.createElement)(s.A,{form:m,layout:"vertical",onFinish:x,initialValues:{zoom_client_id:f?.zoom_client_id,zoom_client_secret:f?.zoom_client_secret,zoom_redirect_url:f?.zoom_redirect_url}},(0,a.createElement)(c.h5,{label:(0,o.__)("Client ID","eventin"),name:"zoom_client_id",placeholder:(0,o.__)("Enter Client ID","eventin"),tooltip:(0,o.__)("Enter Client ID","eventin"),required:!0,rules:[{required:!0,message:(0,o.__)("Client ID is required","eventin")}]}),(0,a.createElement)(c.h5,{label:(0,o.__)("Client Secret Key","eventin"),name:"zoom_client_secret",placeholder:(0,o.__)("Enter Client Secret Key","eventin"),tooltip:(0,o.__)("Enter Client Secret Key","eventin"),required:!0,rules:[{required:!0,message:(0,o.__)("Client Secret Key is required","eventin")}]}),(0,a.createElement)(s.A.Item,{label:(0,o.__)("Redirect URL","eventin"),name:"zoom_redirect_url"},(0,a.createElement)(c.I3,{copyText:h||"",buttonTooltipText:(0,o.__)("Copy Redirect URL","eventin"),icon:(0,a.createElement)(g.d1Z,null),placeholder:(0,o.__)("Enter redirect url","eventin")}))))});n.d(t,["A",0,u])},59255(e,t,n){n.d(t,{U9:()=>g,ew:()=>c.e,ld:()=>m});var a=n(51609),i=n(27723),o=n(67313),l=n(64945),r=n(20710),s=n(22423),c=n(77908);const{Title:d}=o.A,g=["aisentic","migration-tool-for-eventin","eventin-addon-for-tutor-lms"],m=[{key:"1",label:(0,i.__)("Extensions","eventin"),icon:(0,a.createElement)(()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,a.createElement)("path",{d:"M7.5 2.25C7.5 1.42157 8.17157 0.75 9 0.75C9.82843 0.75 10.5 1.42157 10.5 2.25V3H12.75C13.5784 3 14.25 3.67157 14.25 4.5V6.75H15C15.8284 6.75 16.5 7.42157 16.5 8.25C16.5 9.07843 15.8284 9.75 15 9.75H14.25V12.75C14.25 13.5784 13.5784 14.25 12.75 14.25H10.5V15C10.5 15.8284 9.82843 16.5 9 16.5C8.17157 16.5 7.5 15.8284 7.5 15V14.25H5.25C4.42157 14.25 3.75 13.5784 3.75 12.75V9.75H3C2.17157 9.75 1.5 9.07843 1.5 8.25C1.5 7.42157 2.17157 6.75 3 6.75H3.75V4.5C3.75 3.67157 4.42157 3 5.25 3H7.5V2.25Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,a.createElement)(l.A,null)},{key:"2",label:(0,i.__)("Integrations","eventin"),icon:(0,a.createElement)(()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,a.createElement)("path",{d:"M6.75 3.75H4.5C3.67157 3.75 3 4.42157 3 5.25V13.5C3 14.3284 3.67157 15 4.5 15H13.5C14.3284 15 15 14.3284 15 13.5V11.25M11.25 3.75H14.25M14.25 3.75V6.75M14.25 3.75L8.25 9.75",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,a.createElement)(r.A,null)},{key:"3",label:(0,i.__)("Addons","eventin"),icon:(0,a.createElement)(()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,a.createElement)("path",{d:"M3 3.75H7.5V8.25H3V3.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,a.createElement)("path",{d:"M10.5 3.75H15V8.25H10.5V3.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,a.createElement)("path",{d:"M3 9.75H7.5V14.25H3V9.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,a.createElement)("path",{d:"M12.75 9.75V14.25M10.5 12H15",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,a.createElement)(s.A,null)}]},25280(e,t,n){n.d(t,{A:()=>o});var a=n(86087),i=n(19575);function o(e,t,n,o,l,r){const[s,c]=(0,a.useState)(t),[d,g]=(0,a.useState)(!1),[m,u]=(0,a.useState)(!1);return{status:s,isLoading:d,isActive:"off"!==s,buttonLoading:m,toggleModule:async t=>{let a={};if("function"==typeof r){const e=await r(t);if(!1===e)return;e&&"object"==typeof e&&(a=e)}g(!0);const s=await(0,i.A)(e,t?"on":"off",a);s&&(c(t?"on":"off"),await n()),setTimeout(()=>!o&&g(!1),1500),s&&["eventin-addon-for-surecart","eventin-addon-for-fluentcart","rsvp","stripe","paypal"].includes(e)&&l()},updateStatus:async t=>{u(!0),await(0,i.A)(e,t)&&await n(),setTimeout(()=>!o&&u(!1),1500)}}}},25046(e,t,n){n.r(t);var a=n(51609),i=n(29491),o=n(47143),l=n(86087),r=n(27723),s=n(75093),c=n(49603),d=n(24581),g=n(4436),m=n(98739);const u=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),p=(0,i.compose)(u)(function(e){const{extensions:t,isExtensionsLoading:n}=e,[i,o]=(0,l.useState)("1");return(0,a.createElement)(m.ff,{className:"eventin-page-wrapper"},(0,a.createElement)(d.A,{title:(0,r.__)("Extensions","eventin")}),(0,a.createElement)(g.A,{activeTab:i,setActiveTab:o,extensions:t,isExtensionsLoading:n}),(0,a.createElement)(c.A,null),(0,a.createElement)(s._W,null))});n.d(t,["default",0,p])},77908(e,t,n){n.d(t,["e",0,{ZOOM_CONFIG:"zoom",GOOGLE_MEET_CONFIG:"google_meet",EVENTIN_AI_CONFIG:"eventin_ai",GOOGLE_MAP_CONFIG:"google_map",TUTOR_LMS_CONFIG:"eventin-addon-for-tutor-lms",LEARNDASH_CONFIG:"eventin-addon-for-learndash",STRIPE_CONFIG:"stripe",PAYPAL_CONFIG:"paypal"}])},98739(e,t,n){var a=n(27154),i=n(69815);const o=i.A.div`
	background-color: #f4f6fa;
	padding: 12px 32px;
	min-height: 100vh;

	.addons-area-heading {
		width: 50%;
		margin-bottom: 30px;
		@media ( max-width: 768px ) {
			width: 100%;
		}
	}
`,l=i.A.div`
	background: #fff;
	border-radius: 8px;
	margin-bottom: 16px;
	padding: 16px 24px;

	.etn-segment-nav {
		&.ant-segmented {
			background: #f1f1f4;
			border-radius: 10px;
			padding: 4px;
			box-shadow: none;
		}

		.ant-segmented-item {
			border-radius: 7px;
			color: #6b7280;
			font-size: 14px;
			font-weight: 500;
			transition: color 0.5s ease;

			&:hover:not( .ant-segmented-item-selected ) {
				color: #7c3aed;
			}
		}

		.ant-segmented-item-selected {
			color: #7c3aed;
			box-shadow: 0 1px 6px rgba( 0, 0, 0, 0.12 );
		}

		.ant-segmented-thumb {
			border-radius: 7px;
			background: #fff;
		}

		.ant-segmented-item-label {
			padding: 6px 18px;
			min-height: 36px;
			display: flex;
			align-items: center;
		}

		.etn-segment-label {
			display: inline-flex;
			align-items: center;
			gap: 8px;
		}
	}
`,r=i.A.div`
	@keyframes etn-tab-fade-in {
		0% {
			opacity: 0;
			transform: translateY( 12px ) scale( 0.99 );
		}
		60% {
			opacity: 1;
		}
		100% {
			opacity: 1;
			transform: translateY( 0 ) scale( 1 );
		}
	}

	animation: etn-tab-fade-in 0.45s cubic-bezier( 0.22, 1, 0.36, 1 ) forwards;
`,s=i.A.div`
	background: linear-gradient( 135deg, #faf5ff 0%, #fff 60% );
	border: 1px solid #ede9fe;
	border-radius: 8px;
	margin-bottom: 30px;
	padding: 30px;
	position: relative;
	@media ( max-width: 768px ) {
		padding: 20px;
	}
	.etn-featured-heading {
		display: flex;
		align-items: center;
		gap: 10px;
		margin-bottom: 24px;
	}
	.etn-featured-title {
		font-size: 20px;
		color: #212327;
		font-weight: 600;
		margin: 0;
	}
	.etn-featured-description {
		font-size: 14px;
		color: #6b7280;
		font-weight: 400;
	}
	.etn-featured-ribbon {
		display: inline-flex;
		align-items: center;
		padding: 4px 10px;
		border-radius: 4px;
		font-size: 12px;
		font-weight: 600;
		background: #7c3aed;
		color: #fff;
		letter-spacing: 0.4px;
		text-transform: uppercase;
	}
`,c=i.A.div`
	background: #fff;
	border-radius: 8px;
	margin-bottom: 30px;
	padding: 30px;
	@media ( max-width: 768px ) {
		padding: 20px;
	}
	.etn-extension-title {
		font-size: 20px;
		display: inline-block;
		color: #212327;
		font-weight: 600;
	}
	.etn-extension-description {
		font-size: 14px;
		color: #6b7280;
		font-weight: 400;
	}
`,d=(e=>{const t=e.replace("#",""),n=parseInt(3===t.length?t.split("").map(e=>e+e).join(""):t,16);return`${n>>16&255}, ${n>>8&255}, ${255&n}`})(a.VG),g=i.A.div`
	@keyframes etn-card-rise {
		from {
			opacity: 0;
			transform: translateY( 14px );
		}
		to {
			opacity: 1;
			transform: translateY( 0 );
		}
	}

	background: #fff;
	border-radius: 14px;
	margin: 0;
	height: 100%;
	padding-bottom: 22px;
	overflow: hidden;
	position: relative;
	border: 1px solid #ebeef3;
	display: flex;
	flex-direction: column;
	box-shadow: 0 1px 2px rgba( 16, 24, 40, 0.04 ),
		0 1px 3px rgba( 16, 24, 40, 0.06 );
	transition: transform 0.28s cubic-bezier( 0.22, 1, 0.36, 1 ),
		box-shadow 0.28s cubic-bezier( 0.22, 1, 0.36, 1 ),
		border-color 0.28s ease;
	animation: etn-card-rise 0.5s cubic-bezier( 0.22, 1, 0.36, 1 ) both;
	will-change: transform;

	/* Accent bar that wipes in on hover — the card signature */
	&::before {
		content: '';
		position: absolute;
		inset: 0 0 auto 0;
		height: 3px;
		background: linear-gradient(
			90deg,
			${a.VG},
			rgba( ${d}, 0.45 )
		);
		transform: scaleX( 0 );
		transform-origin: left;
		transition: transform 0.32s cubic-bezier( 0.22, 1, 0.36, 1 );
	}

	&:hover {
		transform: translateY( -6px );
		border-color: rgba( ${d}, 0.35 );
		box-shadow: 0 18px 34px -14px rgba( ${d}, 0.28 ),
			0 6px 14px -6px rgba( 16, 24, 40, 0.08 );
	}
	&:hover::before {
		transform: scaleX( 1 );
	}

	.etn-module-card-header {
		padding: 22px 22px 4px;
		display: flex;
		align-items: flex-start;
		justify-content: space-between;
		gap: 20px;
		@media ( max-width: 768px ) {
			flex-wrap: wrap;
		}
	}
	.etn-module-card-header-icon {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		width: 52px;
		height: 52px;
		flex: 0 0 52px;
		border-radius: 13px;
		background: #f6f7fb;
		border: 1px solid #eef0f6;
		transition: background 0.28s ease, border-color 0.28s ease;

		svg,
		img {
			width: 28px;
			height: 28px;
			object-fit: contain;
		}
	}
	&:hover .etn-module-card-header-icon {
		background: rgba( ${d}, 0.08 );
		border-color: rgba( ${d}, 0.22 );
	}
	.etn-module-card-body,
	.etn-module-card-footer {
		padding: 0 22px;
	}
	.etn-module-card-title {
		margin: 0 !important;
		font-size: 16px !important;
		font-weight: 600 !important;
		line-height: 1.35 !important;
		letter-spacing: -0.01em;
		color: #1a1c21 !important;
	}

	.etn-card-desc {
		font-size: 13px;
		line-height: 1.6;
		color: #6b7280;
		.etn-doc-link {
			color: ${a.VG};
			margin-top: 20px;
			a {
				display: inline-flex;
				gap: 8px;
				font-size: 16px !important;
				font-weight: 600 !important;
				text-decoration: none !important;
			}
		}
	}
	.etn-card-desc .ant-typography {
		font-size: 13px;
		line-height: 1.6;
		color: #6b7280;
	}
	.etn-doc-links {
		display: flex;
		align-items: center;
		flex-wrap: wrap;
		gap: 10px;
		margin-top: 16px;
	}
	.etn-doc-link-item {
		display: inline-flex;
		align-items: center;
		gap: 6px;
		font-size: 13px;
		font-weight: 600;
		letter-spacing: 0.01em;
		color: ${a.VG};
		background: rgba( ${d}, 0.07 );
		border: 1px solid rgba( ${d}, 0.12 );
		padding: 6px 12px;
		border-radius: 8px;
		text-decoration: none;
		cursor: pointer;
		transition: background 0.2s ease, border-color 0.2s ease,
			color 0.2s ease;
		svg {
			width: 16px;
			height: 16px;
		}
		&:hover {
			color: ${a.VG};
			background: rgba( ${d}, 0.13 );
			border-color: rgba( ${d}, 0.28 );
		}
	}
	.etn-link-button {
		color: ${a.VG};
		font-size: 15px;
		font-weight: 600;
		margin-top: 10px;
		text-decoration: underline;
		&:hover {
			text-decoration: underline;
			color: ${a.VG};
		}
	}
	@media ( max-width: 768px ) {
		.ant-card .ant-card-body {
			padding: 40px 10px;
		}
	}
	.ant-switch .ant-switch-loading-icon.anticon {
		position: relative;
		top: -2px;
		color: rgba( 0, 0, 0, 0.65 );
		vertical-align: top;
	}

	@media ( prefers-reduced-motion: reduce ) {
		animation: none;
		transition: none;
		&,
		&:hover {
			transform: none;
		}
		&::before {
			transition: none;
		}
	}
`,m=i.A.div`
	display: flex;
	justify-content: flex-end;
	align-items: center;
	gap: 16px;
	margin-top: auto;
	padding: 18px 22px 0;

	/* No action button for this module → collapse the reserved space. */
	&:empty {
		display: none;
		padding: 0;
		margin: 0;
	}
`,u=(i.A.span`
	font-size: 24px;
	margin-right: 10px;
`,{free:"background: #dcfce7; color: #16a34a;",popular:"background: #fef9c3; color: #a16207;",new:"background: #dbeafe; color: #1d4ed8;",pro:"background: #f3e8ff; color: #7c3aed;",trending:"background: #fff7ed; color: #c2410c;",recommended:"background: #ccfbf1; color: #0d9488;",featured:"background: #fef3c7; color: #b45309;",updated:"background: #cffafe; color: #0e7490;",default:"background: #f3f4f6; color: #374151;"}),p=i.A.span`
	display: inline-flex;
	align-items: center;
	padding: 2px 9px;
	border-radius: 999px;
	font-size: 11px;
	font-weight: 600;
	line-height: 1.7;
	letter-spacing: 0.01em;
	white-space: nowrap;
	${({variant:e})=>u[e]||u.default}
`;i.A.div`
	position: absolute;
	height: 85px;
	width: 60px;
	transform: rotate( -45deg );
	top: -38px;
	right: -22px;
	background-color: #faad14;
	color: #fff;
	padding: 5px 16px;
	.anticon {
		position: absolute;
		top: 38px;
		left: 7px;
		transform: rotate( 45deg );
	}
`,n.d(t,["GP",0,l,"Ij",0,p,"JS",0,r,"dQ",0,m,"ff",0,o,"i7",0,s,"nA",0,c,"vi",0,g])}}]);