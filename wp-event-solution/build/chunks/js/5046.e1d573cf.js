"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[5046],{11883(e,t,n){var a=n(51609),o=n(6836);n.d(t,["A",0,({height:e=22,width:t=22})=>(0,o.EZ)(()=>(({height:e,width:t})=>(0,a.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",fill:"none",viewBox:"0 0 20 20"},(0,a.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeLinejoin:"round",strokeWidth:"1.5",d:"M6.25 4.121h7.083c.69 0 1.25.56 1.25 1.25v1.25M12.5 10.788h-5M10 14.121H7.5"}),(0,a.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeWidth:"1.5",d:"M15.414 1.667H5.256c-.414 0-.837.06-1.172.306-1.062.78-1.88 2.517-.228 4.086.464.44 1.112.6 1.75.6h9.63c.662 0 1.847.095 1.847 2.114v6.211a3.34 3.34 0 0 1-3.33 3.35H6.226c-1.836 0-3.172-1.298-3.277-3.274L2.922 4.304"})))({height:e,width:t}))])},57584(e,t,n){n(51609),n(6836)},8358(e,t,n){n.d(t,{A:()=>c});var a=n(51609),o=n(27723),i=n(54725),s=n(58950),l=n(22451),r=n(27154);function c({children:e,...t}){return(0,a.createElement)(l.u,{type:s.rd.type,size:s.rd.size,variantstyle:s.rd.style,onClick:()=>window.open(r.aC,"_blank"),...t},(0,a.createElement)(i.tD6,null),e||(0,o.__)("Get Eventin Pro","wp-event-solution"))}},17026(e,t,n){var a=n(51609),o=n(77278),i=n(16370),s=n(47152),l=n(75063);n.d(t,["A",0,()=>(0,a.createElement)(s.A,{gutter:[16,16]},(0,a.createElement)(i.A,{xs:24,sm:24},(0,a.createElement)(l.A.Input,{active:!0,size:"large",style:{margin:"20px 0"}})),[...Array(6)].map((e,t)=>(0,a.createElement)(i.A,{xs:24,sm:12,md:8,key:t},(0,a.createElement)(o.A,{style:{borderRadius:8}},(0,a.createElement)(l.A.Avatar,{active:!0,size:"large",shape:"circle",style:{marginBottom:16,marginRight:16}}),(0,a.createElement)(l.A.Input,{style:{width:200,marginBottom:8},active:!0}),(0,a.createElement)(l.A.Input,{style:{width:120,marginBottom:8},active:!0}),(0,a.createElement)("div",{style:{display:"flex",gap:10,alignItems:"center",marginTop:16}},(0,a.createElement)(l.A.Button,{style:{width:100},active:!0}),(0,a.createElement)(l.A.Button,{style:{width:100},active:!0}))))))])},19575(e,t,n){var a=n(52619),o=n(27723),i=n(64282);n.d(t,["A",0,async(e,t,n={})=>{try{const o=await i.A.extensions.updateExtension({name:e,status:t,...n});return(0,a.doAction)("eventin_notification",{type:"success",message:o?.message}),!0}catch(e){return(0,a.doAction)("eventin_notification",{type:"error",message:e?.message||(0,o.__)("Update failed! Please check the plugin list and try again.","wp-event-solution")}),!1}}])},49603(e,t,n){var a=n(51609),o=n(47143),i=n(59255),s=n(85890),l=n(4629),r=n(44207),c=n(68186),d=n(49456),p=n(10229),u=n(90860),m=n(16947),g=n(15875),_=n(67008),v=n(1907);n.d(t,["A",0,()=>{const{modalType:e}=(0,o.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:t}=(0,o.useDispatch)("eventin/global"),n=()=>t({modalType:null,modalProps:null});return e&&{[i.ew.ZOOM_CONFIG]:(0,a.createElement)(v.A,{open:!0,onCancel:n}),[i.ew.GOOGLE_MEET_CONFIG]:(0,a.createElement)(r.A,{open:!0,onCancel:n}),[i.ew.EVENTIN_AI_CONFIG]:(0,a.createElement)(s.A,{open:!0,onCancel:n}),[i.ew.GOOGLE_MAP_CONFIG]:(0,a.createElement)(l.A,{open:!0,onCancel:n}),[i.ew.TUTOR_LMS_CONFIG]:(0,a.createElement)(g.A,{open:!0,onCancel:n}),[i.ew.LEARNDASH_CONFIG]:(0,a.createElement)(d.A,{open:!0,onCancel:n}),[i.ew.HUBSPOT_CONFIG]:(0,a.createElement)(c.A,{open:!0,onCancel:n}),[i.ew.STRIPE_CONFIG]:(0,a.createElement)(m.A,{open:!0,onCancel:n}),[i.ew.PAYPAL_CONFIG]:(0,a.createElement)(p.A,{open:!0,onCancel:n}),[i.ew.WHATSAPP_CONFIG]:(0,a.createElement)(_.A,{open:!0,onCancel:n}),[i.ew.RAZORPAY_CONFIG]:(0,a.createElement)(u.A,{open:!0,onCancel:n})}[e]||null}])},22423(e,t,n){var a=n(51609),o=n(29491),i=n(47143),s=n(86087),l=n(27723),r=n(16370),c=n(92911),d=n(47152),p=n(67313),u=n(6660);const{Title:m,Text:g}=p.A,_=(0,i.withDispatch)((e,t,{select:n})=>{const a=e("eventin/global");return{invalidateExtensions:()=>a.invalidateResolution("getExtensions"),invalidateSettings:()=>{a.invalidateResolution("getSettings"),n("eventin/global").getSettings()}}}),v=(0,i.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),h=(0,o.compose)(v,_)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:o,invalidateSettings:i}=e||{},[p,_]=(0,s.useState)([]);return(0,s.useEffect)(()=>{t&&_(Array.isArray(t)&&t?.filter(e=>"addon"===e.type)||[])},[t]),(0,a.createElement)("div",{className:"etn-module-section"},(0,a.createElement)(d.A,{gutter:[30,30]},(0,a.createElement)(r.A,{span:24},(0,a.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,a.createElement)(m,{level:3,className:"etn-extension-title"},(0,l.__)("Addons","wp-event-solution")),(0,a.createElement)(g,{className:"etn-extension-description"}," ",(0,l.__)("Eventin addons","wp-event-solution")))),p.map((e,t)=>(0,a.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(u.A,{module:e,index:t,invalidateExtensions:o,isExtensionsLoading:n,invalidateSettings:i})))))});n.d(t,["A",0,h])},85890(e,t,n){var a=n(51609),o=n(27723),i=n(29491),s=n(47143),l=n(52619),r=n(60742),c=n(75093),d=n(64282);const p=(0,s.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),u=(0,i.compose)(p)(e=>{const{open:t,onCancel:n,extensionsList:i}=e,[p]=r.A.useForm(),{integrationLoading:u}=(0,s.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:m,invalidateResolution:g}=(0,s.useDispatch)("eventin/global"),_=Array.isArray(i)&&i?.find(e=>"eventin_ai"===e.slug),{data:v={}}=_||{data:{}},{eventin_ai_auth_key:h}=v||{},f=async()=>{try{const e=p.getFieldsValue();m({integrationLoading:!0}),(await d.A.settings.updateSettings(e)).eventin_ai_auth_key&&(0,l.doAction)("eventin_notification",{type:"success",message:(0,o.__)("Open AI key updated successfully","wp-event-solution")}),g("getExtensions"),n()}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e.message})}finally{m({integrationLoading:!1,modalType:null})}};return(0,a.createElement)(c.xK,{open:t,onCancel:n,title:(0,o.__)("Eventin AI Configure","wp-event-solution"),onConnect:f,width:500,loading:u,form:p},(0,a.createElement)(r.A,{form:p,layout:"vertical",onFinish:f,initialValues:{eventin_ai_auth_key:h}},(0,a.createElement)(c.h5,{label:(0,o.__)("Open AI Key","wp-event-solution"),name:"eventin_ai_auth_key",placeholder:(0,o.__)("Enter Open AI Key","wp-event-solution"),required:!0,type:"password",rules:[{required:!0,message:(0,o.__)("Open AI Key is required","wp-event-solution")}]})))});n.d(t,["A",0,u])},24581(e,t,n){n.d(t,{A:()=>r});var a=n(51609),o=n(56427),i=n(92911),s=n(18062),l=n(27154);function r(e){const{title:t}=e;return(0,a.createElement)(o.Fill,{name:l.PQ},(0,a.createElement)(i.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,a.createElement)(s.A,{title:t})))}},4436(e,t,n){var a=n(51609),o=n(86087),i=n(11804),s=n(17026),l=n(59255),r=n(98739);const c=l.ld.map(e=>({value:e.key,label:(0,a.createElement)("span",{className:"etn-segment-label"},e.icon,e.label)}));n.d(t,["A",0,function(e){const{activeTab:t,setActiveTab:n,extensions:d}=e||{},[p,u]=(0,o.useState)(!0);if((0,o.useEffect)(()=>{null!=d&&u(!1)},[d]),p)return(0,a.createElement)(s.A,null);const m=l.ld.find(e=>e.key===t)||l.ld[0];return(0,a.createElement)("div",{className:"etn-extensions-container"},(0,a.createElement)(r.GP,null,(0,a.createElement)(i.A,{value:t,onChange:e=>n(e),options:c,className:"etn-segment-nav"})),(0,a.createElement)(r.nA,null,(0,a.createElement)(r.JS,{key:t},m.children)))}])},4629(e,t,n){var a=n(51609),o=n(27723),i=n(29491),s=n(47143),l=n(52619),r=n(60742),c=n(75093),d=n(64282);const p=(0,s.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),u=(0,i.compose)(p)(e=>{const{open:t,onCancel:n,extensionsList:i}=e,[p]=r.A.useForm(),{integrationLoading:u}=(0,s.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:m,invalidateResolution:g}=(0,s.useDispatch)("eventin/global"),_=Array.isArray(i)&&i?.find(e=>"google_map"===e.slug),{data:v={}}=_||{data:{}},{google_api_key:h}=v||{},f=async()=>{try{const e=p.getFieldsValue();m({integrationLoading:!0}),(await d.A.settings.updateSettings(e)).google_api_key&&(0,l.doAction)("eventin_notification",{type:"success",message:(0,o.__)("Google Map API key updated successfully","wp-event-solution")}),g("getExtensions"),n()}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e.message})}finally{m({integrationLoading:!1})}};return(0,a.createElement)(c.xK,{open:t,onCancel:n,title:(0,o.__)("Google Map Configure","wp-event-solution"),onConnect:f,width:500,loading:u,form:p},(0,a.createElement)(r.A,{form:p,layout:"vertical",onFinish:f,initialValues:{google_api_key:h}},(0,a.createElement)(c.h5,{label:(0,o.__)("Map API Key","wp-event-solution"),name:"google_api_key",placeholder:(0,o.__)("Enter Map API Key","wp-event-solution"),tooltip:(0,o.__)("Map API Key","wp-event-solution"),required:!0,rules:[{required:!0,message:(0,o.__)("Map API Key is required","wp-event-solution")}]})))});n.d(t,["A",0,u])},44207(e,t,n){var a=n(51609),o=n(47143),i=n(29491),s=n(52619),l=n(27723),r=n(60742),c=n(75093),d=n(64282),p=n(54725);const u=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),m=(0,i.compose)(u)(e=>{const{open:t,onCancel:n,extensionsList:i}=e,[u]=r.A.useForm(),{integrationLoading:m}=(0,o.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:g,invalidateResolution:_}=(0,o.useDispatch)("eventin/global"),v=Array.isArray(i)&&i?.find(e=>"google_meet"===e.slug),{data:h={}}=v||{data:{}},{google_meet_client_id:f,google_meet_client_secret_key:w,google_meet_redirect_url:E}=h||{},y=async()=>{try{const e=u.getFieldsValue();g({integrationLoading:!0});const t=await d.A.settings.updateSettings(e);_("getExtensions"),t.google_meet_authorize_url&&(window.location.href=t.google_meet_authorize_url)}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{g({integrationLoading:!1})}};return(0,a.createElement)(c.xK,{open:t,onCancel:n,title:(0,l.__)("Google Meet Configure","wp-event-solution"),onConnect:y,width:500,loading:m,form:u},(0,a.createElement)(r.A,{form:u,layout:"vertical",onFinish:y,initialValues:{google_meet_client_id:f,google_meet_client_secret_key:w,google_meet_redirect_url:E}},(0,a.createElement)(c.h5,{label:(0,l.__)("Client ID","wp-event-solution"),name:"google_meet_client_id",placeholder:(0,l.__)("Enter Client ID","wp-event-solution"),tooltip:(0,l.__)("Enter Client ID","wp-event-solution"),required:!0,type:"password",rules:[{required:!0,message:(0,l.__)("Client ID is required","wp-event-solution")}]}),(0,a.createElement)(c.h5,{label:(0,l.__)("Client Secret Key","wp-event-solution"),name:"google_meet_client_secret_key",placeholder:(0,l.__)("Enter Client Secret Key","wp-event-solution"),tooltip:(0,l.__)("Enter Client Secret Key","wp-event-solution"),required:!0,type:"password",rules:[{required:!0,message:(0,l.__)("Client Secret Key is required","wp-event-solution")}]}),(0,a.createElement)(r.A.Item,{label:(0,l.__)("Authorized Redirect URL","wp-event-solution"),name:"google_meet_redirect_url"},(0,a.createElement)(c.I3,{copyText:E,buttonTooltipText:(0,l.__)("Copy Redirect URL","wp-event-solution"),icon:(0,a.createElement)(p.d1Z,null),placeholder:(0,l.__)("Enter redirect url","wp-event-solution")}))))});n.d(t,["A",0,m])},68186(e,t,n){var a=n(51609),o=n(47143),i=n(86087),s=n(52619),l=n(27723),r=n(82654),c=n(92911),d=n(60742),p=n(75093),u=n(7638),m=n(64282);n.d(t,["A",0,({open:e,onCancel:t})=>{const[n]=d.A.useForm(),[g,_]=(0,i.useState)(null),[v,h]=(0,i.useState)(!1),[f,w]=(0,i.useState)([]),{integrationLoading:E}=(0,o.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:y,invalidateResolution:x}=(0,o.useDispatch)("eventin/global");(0,i.useEffect)(()=>{if(!e)return;let t=!0;return m.A.hubspot.getStatus().then(e=>{t&&_(e)}).catch(()=>{t&&_(null)}),()=>{t=!1}},[e]);const b=(e,t)=>(0,s.doAction)("eventin_notification",{type:e,message:t}),A=async(e,t)=>{try{h(!0);const n=await e();_(n),x("getExtensions"),b("success",t)}catch(e){b("error",e.message)}finally{h(!1)}},k=!!g?.connected,C=!!g?.custom_events_enabled;return(0,a.createElement)(p.xK,{open:e,onCancel:t,title:(0,l.__)("HubSpot Configure","wp-event-solution"),onConnect:async()=>{const e=n.getFieldValue("hubspot_token")?.trim();if(!e)return g?.connected?void t?.():void b("error",(0,l.__)("Enter your HubSpot Service Key.","wp-event-solution"));try{y({integrationLoading:!0});const t=await m.A.hubspot.connect(e);_(t),w(t?.warnings||[]),n.setFieldValue("hubspot_token",""),x("getExtensions"),b("success",(0,l.__)("HubSpot connected","wp-event-solution"))}catch(e){b("error",e.message)}finally{y({integrationLoading:!1})}},width:560,loading:E},(0,a.createElement)(d.A,{form:n,layout:"vertical"},(0,a.createElement)(p.h5,{label:(0,l.__)("Service Key","wp-event-solution"),name:"hubspot_token",placeholder:k?g?.token_hint||(0,l.__)("Token stored","wp-event-solution"):(0,l.__)("Paste your HubSpot Service Key","wp-event-solution")})),(0,a.createElement)(p.EY,{sx:{display:"block",marginTop:"-10px"}},(0,l.__)("Create a Service Key in HubSpot under Settings → Integrations → Service Keys. An existing legacy private app token also works. Contact sync needs crm.objects.contacts.read, crm.objects.contacts.write, crm.schemas.contacts.read and crm.schemas.contacts.write. Timeline event tracking additionally needs behavioral_events.event_definitions.read_write and analytics.behavioral_events.send.","wp-event-solution")),k&&(0,a.createElement)(c.A,{vertical:!0,gap:12,style:{marginTop:20}},(0,a.createElement)(r.A,{type:"success",showIcon:!0,message:(0,l.__)("HubSpot is connected.","wp-event-solution"),description:g?.properties_provisioned?(0,l.__)("Eventin contact properties are in place.","wp-event-solution"):(0,l.__)("Eventin contact properties have not been created yet.","wp-event-solution")}),C&&(0,a.createElement)(r.A,{type:"success",showIcon:!0,message:(0,l.__)("Timeline event tracking is active.","wp-event-solution")}),f.map(e=>(0,a.createElement)(r.A,{key:e,type:"warning",showIcon:!0,message:e})),(0,a.createElement)(c.A,{gap:10,wrap:"wrap"},(0,a.createElement)(u.Ay,{variant:u.Vt,loading:v,onClick:()=>A(()=>m.A.hubspot.provisionProperties(),(0,l.__)("Eventin properties synced to HubSpot","wp-event-solution"))},(0,l.__)("Sync Properties","wp-event-solution")),(0,a.createElement)(u.Ay,{variant:u.Vt,loading:v,onClick:()=>A(()=>m.A.hubspot.disconnect(),(0,l.__)("HubSpot disconnected","wp-event-solution"))},(0,l.__)("Disconnect","wp-event-solution")))))}])},20710(e,t,n){var a=n(51609),o=n(29491),i=n(47143),s=n(86087),l=n(27723),r=n(16370),c=n(92911),d=n(47152),p=n(67313),u=n(6660);const{Title:m,Text:g}=p.A,_=(0,i.withDispatch)(e=>{const t=e("eventin/global");return{invalidateExtensions:()=>t.invalidateResolution("getExtensions")}}),v=(0,i.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),h=(0,o.compose)(v,_)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:o}=e||{},[i,p]=(0,s.useState)([]);return(0,s.useEffect)(()=>{t&&p(Array.isArray(t)&&t?.filter(e=>"integration"===e.type)||[])},[t]),(0,a.createElement)("div",{className:"etn-module-section"},(0,a.createElement)(d.A,{gutter:[30,30]},(0,a.createElement)(r.A,{span:24},(0,a.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,a.createElement)(m,{level:3,className:"etn-extension-title"},(0,l.__)("Integrations","wp-event-solution")),(0,a.createElement)(g,{className:"etn-extension-description"}," ",(0,l.__)("Third-party integrations","wp-event-solution")))),i.map((e,t)=>(0,a.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(u.A,{module:e,index:t,invalidateExtensions:o,isExtensionsLoading:n})))))});n.d(t,["A",0,h])},49456(e,t,n){var a=n(51609),o=n(47143),i=n(86087),s=n(27723),l=n(52619),r=n(69815),c=n(38181),d=n(52741),p=n(92911),u=n(60742),m=n(51643),g=n(75093),_=n(7638),v=n(64282);const h=r.A.div`
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
`,f={enroll_mode:"all_attendees",auto_create_users:!0,send_credentials:!0,unenroll_on_refund:!0},w=e=>"on"===e||!0===e;n.d(t,["A",0,({open:e,onCancel:t})=>{const[n]=u.A.useForm(),[r,E]=(0,i.useState)(f),[y,x]=(0,i.useState)(!1),[b,A]=(0,i.useState)(!0),{integrationLoading:k}=(0,o.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:C}=(0,o.useDispatch)("eventin/global");(0,i.useEffect)(()=>{let e=!0;return(async()=>{try{const t=await v.A.learnDash.getSettings();if(!e)return;const a={enroll_mode:t?.enroll_mode||f.enroll_mode,auto_create_users:w(t?.auto_create_users),send_credentials:w(t?.send_credentials),unenroll_on_refund:w(t?.unenroll_on_refund)};E(a),n.setFieldsValue(a)}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e?.message||(0,s.__)("Failed to load settings","wp-event-solution")})}finally{e&&A(!1)}})(),()=>{e=!1}},[n]);const S=async()=>{try{const e=await n.validateFields();x(!0),C({integrationLoading:!0});const a={enroll_mode:e.enroll_mode,auto_create_users:e.auto_create_users?"on":"off",send_credentials:e.send_credentials?"on":"off",unenroll_on_refund:e.unenroll_on_refund?"on":"off"};await v.A.learnDash.updateSettings(a),(0,l.doAction)("eventin_notification",{type:"success",message:(0,s.__)("LearnDash settings saved","wp-event-solution")}),t()}catch(e){if(e?.errorFields)return;(0,l.doAction)("eventin_notification",{type:"error",message:e?.message||(0,s.__)("Failed to save settings","wp-event-solution")})}finally{x(!1),C({integrationLoading:!1})}},L=(0,a.createElement)(p.A,{align:"center",justify:"flex-end",gap:10},(0,a.createElement)(_.Ay,{variant:_.zB,onClick:S,loading:y||k,type:"submit"},(0,s.__)("Save Changes","wp-event-solution")));return(0,a.createElement)(g.xK,{open:e,onCancel:t,title:(0,s.__)("LearnDash Integration for Eventin","wp-event-solution"),width:580,loading:y||b,footerContent:L,form:n},(0,a.createElement)(h,null,(0,a.createElement)(u.A,{form:n,layout:"horizontal",labelCol:{span:14},wrapperCol:{span:10},labelAlign:"left",colon:!1,onFinish:S,initialValues:r},(0,a.createElement)(u.A.Item,{label:(0,s.__)("Who gets enrolled?","wp-event-solution"),name:"enroll_mode",layout:"vertical",labelCol:{span:24},wrapperCol:{span:24}},(0,a.createElement)(m.Ay.Group,null,(0,a.createElement)(p.A,{vertical:!0,gap:8},(0,a.createElement)(m.Ay,{value:"purchaser"},(0,s.__)("Purchaser only","wp-event-solution")),(0,a.createElement)(m.Ay,{value:"all_attendees"},(0,s.__)("Every registered attendee","wp-event-solution"))))),(0,a.createElement)(d.A,{style:{marginBlock:"5px"}}),(0,a.createElement)(u.A.Item,{name:"auto_create_users",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,s.__)("Create WP accounts for guest attendees","wp-event-solution"))),(0,a.createElement)(u.A.Item,{name:"send_credentials",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,s.__)("Email login credentials to new users","wp-event-solution"))),(0,a.createElement)(u.A.Item,{name:"unenroll_on_refund",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,s.__)("Revoke course access on refund / cancellation / failure","wp-event-solution"))))))}])},64945(e,t,n){n.d(t,{A:()=>w});var a=n(51609),o=n(29491),i=n(47143),s=n(86087),l=n(27723),r=n(16370),c=n(92911),d=n(47152),p=n(67313),u=n(6660),m=n(59255),g=n(98739);const{Title:_,Text:v}=p.A,h=(0,i.withDispatch)((e,t,{select:n})=>{const a=e("eventin/global");return{invalidateExtensions:()=>a.invalidateResolution("getExtensions"),invalidateSettings:()=>{a.invalidateResolution("getSettings"),n("eventin/global").getSettings()}}}),f=(0,i.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),w=(0,o.compose)(f,h)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:o,invalidateSettings:i}=e||{},[p,h]=(0,s.useState)([]),[f,w]=(0,s.useState)([]),[E,y]=(0,s.useState)([]),[x,b]=(0,s.useState)([]);return(0,s.useEffect)(()=>{if(!t)return;const e=Object.values(t),n=m.U9.map(t=>e.find(e=>e.slug===t||e.name===t)).filter(Boolean),a=new Set(n.flatMap(e=>[e.slug,e.name]).filter(Boolean)),o=t=>e.filter(e=>e.type===t&&!(e=>a.has(e.slug)||a.has(e.name))(e));b(n),h(o("module")),w(o("addon")),y(o("integration"))},[t]),(0,a.createElement)("div",{className:"etn-module-section"},x.length>0&&(0,a.createElement)(g.i7,null,(0,a.createElement)("div",{className:"etn-featured-heading"},(0,a.createElement)("span",{className:"etn-featured-ribbon"},(0,l.__)("Featured","wp-event-solution"))),(0,a.createElement)(d.A,{gutter:[30,30]},x.map((e,t)=>(0,a.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(u.A,{module:e,index:t,invalidateExtensions:o,isExtensionsLoading:n,invalidateSettings:i}))))),(0,a.createElement)(d.A,{gutter:[30,30]},(0,a.createElement)(r.A,{span:24},(0,a.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,a.createElement)(_,{level:3,className:"etn-extension-title"},(0,l.__)("Modules","wp-event-solution")),(0,a.createElement)(v,{className:"etn-extension-description"}," ",(0,l.__)("Eventin modules","wp-event-solution")))),p.map((e,t)=>(0,a.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(u.A,{module:e,index:t,invalidateExtensions:o,isExtensionsLoading:n})))),(0,a.createElement)(d.A,{gutter:[30,30]},(0,a.createElement)(r.A,{span:24},(0,a.createElement)(c.A,{justify:"space-between",align:"center",gap:10,style:{marginTop:"30px"}},(0,a.createElement)(_,{level:3,className:"etn-extension-title"},(0,l.__)("Addons","wp-event-solution")),(0,a.createElement)(v,{className:"etn-extension-description"}," ",(0,l.__)("Eventin addons","wp-event-solution")))),f.map((e,t)=>(0,a.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(u.A,{module:e,index:t,invalidateExtensions:o,isExtensionsLoading:n,invalidateSettings:i})))),(0,a.createElement)(d.A,{gutter:[30,30]},(0,a.createElement)(r.A,{span:24},(0,a.createElement)(c.A,{justify:"space-between",align:"center",gap:10,style:{marginTop:"30px"}},(0,a.createElement)(_,{level:3,className:"etn-extension-title"},(0,l.__)("Integrations","wp-event-solution")),(0,a.createElement)(v,{className:"etn-extension-description"}," ",(0,l.__)("Eventin integrations","wp-event-solution")))),E.map((e,t)=>(0,a.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,a.createElement)(u.A,{module:e,index:t,invalidateExtensions:o,isExtensionsLoading:n})))))})},10229(e,t,n){var a=n(51609),o=n(47143),i=n(29491),s=n(52619),l=n(27723),r=n(38181),c=n(60742),d=n(75093),p=n(64282);const u=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),m=(0,i.compose)(u)(e=>{const{open:t,onCancel:n,extensionsList:i}=e,[u]=c.A.useForm(),{integrationLoading:m}=(0,o.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:g,invalidateResolution:_}=(0,o.useDispatch)("eventin/global"),v=Array.isArray(i)&&i?.find(e=>"paypal"===e.slug),{data:h={}}=v||{data:{}},{paypal_client_id:f,paypal_client_secret:w,paypal_sandbox:E}=h||{},y=async()=>{try{const e=u.getFieldsValue();g({integrationLoading:!0}),await p.A.settings.updateSettings(e),_("getExtensions"),(0,s.doAction)("eventin_notification",{type:"success",message:(0,l.__)("PayPal settings saved","wp-event-solution")}),n?.()}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{g({integrationLoading:!1})}};return(0,a.createElement)(d.xK,{open:t,onCancel:n,title:(0,l.__)("PayPal Configure","wp-event-solution"),onConnect:y,width:500,loading:m,form:u},(0,a.createElement)(c.A,{form:u,layout:"vertical",onFinish:y,initialValues:{paypal_client_id:f,paypal_client_secret:w,paypal_sandbox:E}},(0,a.createElement)(d.h5,{label:(0,l.__)("PayPal Client ID","wp-event-solution"),name:"paypal_client_id",placeholder:(0,l.__)("Enter client id","wp-event-solution"),required:!0,rules:[{required:!0,message:(0,l.__)("Client ID is required","wp-event-solution")}]}),(0,a.createElement)(d.h5,{label:(0,l.__)("PayPal Secret Key","wp-event-solution"),name:"paypal_client_secret",placeholder:(0,l.__)("Enter secret key","wp-event-solution"),required:!0,rules:[{required:!0,message:(0,l.__)("Secret key is required","wp-event-solution")}]}),(0,a.createElement)(c.A.Item,{name:"paypal_sandbox",valuePropName:"checked"},(0,a.createElement)(r.A,null,(0,l.__)("Test payments with PayPal sandbox","wp-event-solution")))))});n.d(t,["A",0,m])},90860(e,t,n){var a=n(51609),o=n(47143),i=n(29491),s=n(52619),l=n(27723),r=n(60742),c=n(67313),d=n(75093),p=n(64282);const{Text:u,Paragraph:m}=c.A,g=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),_=(0,i.compose)(g)(e=>{const{open:t,onCancel:n,extensionsList:i}=e,[c]=r.A.useForm(),{integrationLoading:g}=(0,o.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:_,invalidateResolution:v}=(0,o.useDispatch)("eventin/global"),h=Array.isArray(i)&&i?.find(e=>"razorpay"===e.slug),{data:f={}}=h||{data:{}},{razorpay_key_id:w,razorpay_key_secret:E,razorpay_webhook_secret:y,razorpay_webhook_url:x}=f||{},b=async()=>{try{const e=c.getFieldsValue();_({integrationLoading:!0}),await p.A.settings.updateSettings(e),v("getExtensions"),(0,s.doAction)("eventin_notification",{type:"success",message:(0,l.__)("Razorpay settings saved","wp-event-solution")}),n?.()}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{_({integrationLoading:!1})}};return(0,a.createElement)(d.xK,{open:t,onCancel:n,title:(0,l.__)("Razorpay Configure","wp-event-solution"),onConnect:b,width:500,loading:g,form:c},(0,a.createElement)(r.A,{form:c,layout:"vertical",onFinish:b,initialValues:{razorpay_key_id:w,razorpay_key_secret:E,razorpay_webhook_secret:y}},(0,a.createElement)(d.h5,{label:(0,l.__)("Razorpay Key ID","wp-event-solution"),name:"razorpay_key_id",placeholder:(0,l.__)("rzp_live_... or rzp_test_...","wp-event-solution"),required:!0,rules:[{required:!0,message:(0,l.__)("Key ID is required","wp-event-solution")},{pattern:/^rzp_(test|live)_/,message:(0,l.__)("Key ID starts with rzp_test_ or rzp_live_","wp-event-solution")}]}),(0,a.createElement)(d.h5,{label:(0,l.__)("Razorpay Key Secret","wp-event-solution"),name:"razorpay_key_secret",placeholder:(0,l.__)("Enter key secret","wp-event-solution"),required:!0,rules:[{required:!0,message:(0,l.__)("Key Secret is required","wp-event-solution")}]}),(0,a.createElement)(d.h5,{label:(0,l.__)("Webhook Secret","wp-event-solution"),name:"razorpay_webhook_secret",placeholder:(0,l.__)("Enter webhook secret","wp-event-solution")}),x&&(0,a.createElement)("div",{style:{marginBottom:"16px"}},(0,a.createElement)(u,{strong:!0},(0,l.__)("Webhook URL","wp-event-solution")),(0,a.createElement)(m,{copyable:{text:x},style:{marginBottom:"4px"}},(0,a.createElement)(u,{code:!0},x)),(0,a.createElement)(u,{type:"secondary",style:{fontSize:"12px"}},(0,l.__)("Add this URL in Razorpay Dashboard → Webhooks. Select the events order.paid, payment.failed and refund.processed, and use the same webhook secret as above.","wp-event-solution")))))});n.d(t,["A",0,_])},37762(e,t,n){var a=n(51609),o=n(27723),i=n(67313),s=n(11883);const{Text:l}=i.A,r=()=>(0,a.createElement)("svg",{width:"16",height:"16",viewBox:"0 0 16 16",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,a.createElement)("circle",{cx:"8",cy:"8",r:"7",stroke:"currentColor",strokeWidth:"1.5"}),(0,a.createElement)("path",{d:"M6.5 5.5L11 8L6.5 10.5V5.5Z",fill:"currentColor"})),c=({href:e,children:t})=>(0,a.createElement)("a",{href:e,target:"_blank",rel:"noreferrer",className:"etn-doc-link-item"},t);n.d(t,["v",0,({description:e,notice:t,doc_link:n,video_link:i})=>(0,a.createElement)("div",{className:"etn-card-desc"},(0,a.createElement)(l,null,e.length>90?e.slice(0,90).concat("..."):e),t&&(0,a.createElement)(l,{style:{display:"flex",color:"var(--etn-warning, #ff7129)",marginTop:"10px"}},t),(0,a.createElement)("div",{className:"etn-doc-links"},(0,a.createElement)(c,{href:n},(0,a.createElement)(s.A,null)," ",(0,o.__)("Learn More","wp-event-solution")),i&&(0,a.createElement)(c,{href:i},(0,a.createElement)(r,null)," ",(0,o.__)("Tutorial","wp-event-solution"))))])},32066(e,t,n){var a=n(51609),o=n(86087),i=n(27723),s=n(47143),l=n(92911),r=n(7638),c=n(64282),d=n(52619),p=n(8358),u=n(77908);const m=[u.e.TUTOR_LMS_CONFIG,u.e.LEARNDASH_CONFIG],g=new Set(Object.values(u.e)),_={fontSize:"14px",fontWeight:500,height:"40px",padding:"0 22px",borderRadius:"8px"},v="zoom";n.d(t,["x",0,e=>{const{type:t,isProActive:n,is_pro:u,deps:h,loading:f,data:w}=e,{setEventinState:E}=(0,s.useDispatch)("eventin/global"),{actions:y,btnStyle:x}=(({status:e,type:t,slug:n,onChangeStatus:a,upgrade_link:s,download_link:l,settings_link:c,deps:d,upgrade:p,setEventinState:u})=>{const v=(0,o.useCallback)(()=>{u({modalType:n})},[n,u]);if("integration"===t&&d?.length>0)return"off"===e?{actions:[],btnStyle:_}:{actions:{install:[{label:(0,i.__)("Activate","wp-event-solution"),variant:r.zB,onClick:()=>a("activate")}],activate:[{label:(0,i.__)("Deactivate","wp-event-solution"),variant:r.Rm,onClick:()=>a("deactivate")}]}[e]||[{label:(0,i.__)("Install","wp-event-solution"),variant:r.zB,onClick:()=>a("install")}],btnStyle:_};const h={on:()=>"integration"===t||"eventin_ai"===n?g.has(n)?[{label:(0,i.__)("Configure","wp-event-solution"),variant:r.Rm,onClick:v}]:[]:d?.length||"addon"===t?[{label:(0,i.__)("Install","wp-event-solution"),variant:r.zB,onClick:()=>a("install")}]:[],install:()=>[{label:(0,i.__)("Activate","wp-event-solution"),variant:r.zB,onClick:()=>a("activate")}],upgrade:()=>[{label:(0,i.__)("Download","wp-event-solution"),variant:r.zB,href:s,target:"_blank"}],activate:()=>{const e=m.includes(n)?{label:(0,i.__)("Configure","wp-event-solution"),variant:r.Rm,onClick:v}:c&&{label:(0,i.__)("Configure","wp-event-solution"),variant:r.Rm,href:c,target:"_blank"};return[{label:(0,i.__)("Deactivate","wp-event-solution"),variant:r.Rm,onClick:()=>a("deactivate")},e].filter(Boolean)}},f=h[e]?.()||[];return l&&"upgrade"!==e&&f.push({label:(0,i.__)("Download","wp-event-solution"),variant:r.zB,href:l,download:!0}),{actions:f,btnStyle:_}})({...e,setEventinState:E}),[b,A]=(0,o.useState)(!1),[k,C]=(0,o.useState)({zoom_connected:"yes"===w?.zoom_connected,google_meet_connected:"yes"===w?.google_meet_connected}),S=(0,o.useCallback)(async e=>{try{A(!0),await c.A.settings.updateSettings(e)&&(C(t=>({...t,zoom_connected:e?.zoom_connected,google_meet_connected:e?.google_meet_connected})),(0,d.doAction)("eventin_notification",{type:"success",message:(0,i.__)("Disconnected successfully","wp-event-solution")}))}catch(e){(0,d.doAction)("eventin_notification",{type:"error",message:e.message})}finally{A(!1)}},[]),L=(0,o.useCallback)((e,n,o)=>"integration"===t&&n?(0,a.createElement)(r.Ay,{style:x,variant:e===v?r.Vt:r.zB,onClick:()=>S(o),loading:b,disabled:b},(0,i.__)("Disconnect","wp-event-solution")):null,[t,x,S,b]);return(0,o.useMemo)(()=>{if(!n&&u)return(0,a.createElement)(p.A,{sx:{height:"36px",fontSize:"14px"}});if("module"===t&&!h?.length)return null;if(!y.length)return null;const e=L(v,k?.zoom_connected,{zoom_token:{},zoom_connected:!1}),o=L("google_meet",k?.google_meet_connected,{google_token:{},google_meet_connected:!1});return(0,a.createElement)(l.A,{gap:20,wrap:"wrap"},e,o,y.map(({label:e,style:t,...n},o)=>(0,a.createElement)(r.Ay,{key:o,...n,style:{...x,...t||{}},loading:f},e)))},[y,x,h,f,n,u,t,k,L])}])},70334(e,t,n){var a=n(51609),o=n(43960);n.d(t,["j",0,({checked:e,loading:t,disabled:n,onChange:i})=>(0,a.createElement)(o.A,{className:"etn-addon-module-switch",loading:t,checked:e,onChange:i,disabled:n})])},6660(e,t,n){var a=n(51609),o=n(27723),i=n(19549),s=n(67313),l=(n(57584),n(64282)),r=n(25280),c=n(98739),d=n(37762),p=n(32066),u=n(70334);const{Title:m}=s.A,g=["stripe","paypal","razorpay"],_=e=>{if(null==e)return!1;if("boolean"==typeof e)return e;if("number"==typeof e)return 0!==e;if("string"==typeof e){const t=e.trim().toLowerCase();return""!==t&&"0"!==t&&"false"!==t}return!!e};n.d(t,["A",0,({module:e,invalidateExtensions:t,isExtensionsLoading:n,invalidateSettings:s,index:v=0})=>{const{name:h,title:f,description:w,status:E,notice:y,icon:x,settings_link:b,doc_link:A,video_link:k,is_pro:C,upgrade_link:S,download_link:L,plugin_toggle:I=!1,upgrade:z,deps:N,type:F,slug:T,badge_tags:P=[],data:R={}}=e||{},V=g.includes(T)?async e=>{if(!e)return{};let t=null;try{t=await l.A.settings.getSettings()}catch(e){t=null}const n=(e=>{if(!e)return[];const t=[];return"woocommerce"===e.sell_tickets&&t.push("WooCommerce"),_(e.surecart_status)&&t.push("SureCart"),_(e.fluentcart_status)&&t.push("FluentCart"),t})(t);return 0===n.length?{}:new Promise(e=>{i.A.confirm({title:(0,o.__)("Disable other payment methods?","wp-event-solution"),content:n.join(", ")+" "+(0,o.__)("is currently enabled. Enabling this payment method will disable it. Continue?","wp-event-solution"),okText:(0,o.__)("Continue","wp-event-solution"),cancelText:(0,o.__)("Cancel","wp-event-solution"),onOk:()=>e({clear_conflicts:!0}),onCancel:()=>e(!1)})})}:void 0,{status:D,isLoading:O,buttonLoading:G,isActive:M,toggleModule:q,updateStatus:K}=(0,r.A)(h,E,t,n,s,V,I),H=!!window.localized_data_obj.evnetin_pro_active,j=60*Math.min(v,8)+"ms";return(0,a.createElement)(c.vi,{style:{animationDelay:j}},(0,a.createElement)("div",{className:"etn-module-card-header"},"string"!=typeof(B=x)||B.trim().startsWith("<")?(0,a.createElement)("div",{className:"etn-module-card-header-icon","aria-hidden":"true",dangerouslySetInnerHTML:{__html:x}}):(0,a.createElement)("div",{className:"etn-module-card-header-icon"},(0,a.createElement)("img",{src:x,alt:"","aria-hidden":"true",width:"28",height:"28",decoding:"async"})),!(!H&&C)&&(0,a.createElement)(u.j,{checked:M,onChange:q,loading:O})),(0,a.createElement)("div",{className:"etn-module-card-body"},(0,a.createElement)("div",{style:{display:"flex",alignItems:"center",flexWrap:"wrap",gap:"8px",margin:"16px 0 10px 0"}},(0,a.createElement)(m,{level:4,className:"etn-module-card-title"},f),P.map(e=>(0,a.createElement)(c.Ij,{key:e,variant:e.toLowerCase()},e))),(0,a.createElement)(d.v,{description:w,notice:y,doc_link:A,video_link:k})),(0,a.createElement)(c.dQ,null,(0,a.createElement)(p.x,{status:E,loading:G,onChangeStatus:K,upgrade:z,upgrade_link:S,download_link:L,settings_link:b,type:F,slug:T,deps:N,is_pro:C,isProActive:H,data:R})));var B}])},16947(e,t,n){var a=n(51609),o=n(47143),i=n(29491),s=n(52619),l=n(27723),r=n(60742),c=n(75093),d=n(64282);const p=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),u=(0,i.compose)(p)(e=>{const{open:t,onCancel:n,extensionsList:i}=e,[p]=r.A.useForm(),{integrationLoading:u}=(0,o.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:m,invalidateResolution:g}=(0,o.useDispatch)("eventin/global"),_=Array.isArray(i)&&i?.find(e=>"stripe"===e.slug),{data:v={}}=_||{data:{}},{stripe_live_publishable_key:h,stripe_live_secret_key:f,etn_stripe_webhook_secret:w}=v||{},E=async()=>{try{const e=p.getFieldsValue();m({integrationLoading:!0}),await d.A.settings.updateSettings(e),g("getExtensions"),(0,s.doAction)("eventin_notification",{type:"success",message:(0,l.__)("Stripe settings saved","wp-event-solution")}),n?.()}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{m({integrationLoading:!1})}};return(0,a.createElement)(c.xK,{open:t,onCancel:n,title:(0,l.__)("Stripe Configure","wp-event-solution"),onConnect:E,width:500,loading:u,form:p},(0,a.createElement)(r.A,{form:p,layout:"vertical",onFinish:E,initialValues:{stripe_live_publishable_key:h,stripe_live_secret_key:f,etn_stripe_webhook_secret:w}},(0,a.createElement)(c.h5,{label:(0,l.__)("Publishable Key","wp-event-solution"),name:"stripe_live_publishable_key",placeholder:(0,l.__)("Enter publishable key","wp-event-solution"),required:!0,rules:[{required:!0,message:(0,l.__)("Publishable key is required","wp-event-solution")}]}),(0,a.createElement)(c.h5,{label:(0,l.__)("Secret Key","wp-event-solution"),name:"stripe_live_secret_key",placeholder:(0,l.__)("Enter secret key","wp-event-solution"),required:!0,rules:[{required:!0,message:(0,l.__)("Secret key is required","wp-event-solution")}]}),(0,a.createElement)(c.h5,{label:(0,l.__)("Webhook Signing Secret","wp-event-solution"),name:"etn_stripe_webhook_secret",placeholder:(0,l.__)("Enter webhook signing secret (whsec_...)","wp-event-solution")})))});n.d(t,["A",0,u])},15875(e,t,n){var a=n(51609),o=n(47143),i=n(86087),s=n(27723),l=n(52619),r=n(69815),c=n(38181),d=n(52741),p=n(92911),u=n(60742),m=n(51643),g=n(75093),_=n(7638),v=n(64282);const h=r.A.div`
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
`,f={enroll_mode:"all_attendees",auto_create_users:!0,send_credentials:!0,unenroll_on_refund:!0},w=e=>"on"===e||!0===e;n.d(t,["A",0,({open:e,onCancel:t})=>{const[n]=u.A.useForm(),[r,E]=(0,i.useState)(f),[y,x]=(0,i.useState)(!1),[b,A]=(0,i.useState)(!0),{integrationLoading:k}=(0,o.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:C}=(0,o.useDispatch)("eventin/global");(0,i.useEffect)(()=>{let e=!0;return(async()=>{try{const t=await v.A.tutorLms.getSettings();if(!e)return;const a={enroll_mode:t?.enroll_mode||f.enroll_mode,auto_create_users:w(t?.auto_create_users),send_credentials:w(t?.send_credentials),unenroll_on_refund:w(t?.unenroll_on_refund)};E(a),n.setFieldsValue(a)}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e?.message||(0,s.__)("Failed to load settings","wp-event-solution")})}finally{e&&A(!1)}})(),()=>{e=!1}},[n]);const S=async()=>{try{const e=await n.validateFields();x(!0),C({integrationLoading:!0});const a={enroll_mode:e.enroll_mode,auto_create_users:e.auto_create_users?"on":"off",send_credentials:e.send_credentials?"on":"off",unenroll_on_refund:e.unenroll_on_refund?"on":"off"};await v.A.tutorLms.updateSettings(a),(0,l.doAction)("eventin_notification",{type:"success",message:(0,s.__)("Tutor LMS settings saved","wp-event-solution")}),t()}catch(e){if(e?.errorFields)return;(0,l.doAction)("eventin_notification",{type:"error",message:e?.message||(0,s.__)("Failed to save settings","wp-event-solution")})}finally{x(!1),C({integrationLoading:!1})}},L=(0,a.createElement)(p.A,{align:"center",justify:"flex-end",gap:10},(0,a.createElement)(_.Ay,{variant:_.zB,onClick:S,loading:y||k,type:"submit"},(0,s.__)("Save Changes","wp-event-solution")));return(0,a.createElement)(g.xK,{open:e,onCancel:t,title:(0,s.__)("Tutor LMS Integration for Eventin","wp-event-solution"),width:580,loading:y||b,footerContent:L,form:n},(0,a.createElement)(h,null,(0,a.createElement)(u.A,{form:n,layout:"horizontal",labelCol:{span:14},wrapperCol:{span:10},labelAlign:"left",colon:!1,onFinish:S,initialValues:r},(0,a.createElement)(u.A.Item,{label:(0,s.__)("Who gets enrolled?","wp-event-solution"),name:"enroll_mode",layout:"vertical",labelCol:{span:24},wrapperCol:{span:24}},(0,a.createElement)(m.Ay.Group,null,(0,a.createElement)(p.A,{vertical:!0,gap:8},(0,a.createElement)(m.Ay,{value:"purchaser"},(0,s.__)("Purchaser only","wp-event-solution")),(0,a.createElement)(m.Ay,{value:"all_attendees"},(0,s.__)("Every registered attendee","wp-event-solution"))))),(0,a.createElement)(d.A,{style:{marginBlock:"5px"}}),(0,a.createElement)(u.A.Item,{name:"auto_create_users",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,s.__)("Create WP accounts for guest attendees","wp-event-solution"))),(0,a.createElement)(u.A.Item,{name:"send_credentials",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,s.__)("Email login credentials to new users","wp-event-solution"))),(0,a.createElement)(u.A.Item,{name:"unenroll_on_refund",valuePropName:"checked"},(0,a.createElement)(c.A,{className:"etn-checkbox-label"},(0,s.__)("Unenroll on refund / cancellation / failure","wp-event-solution"))))))}])},67008(e,t,n){var a=n(51609),o=n(47143),i=n(29491),s=n(52619),l=n(27723),r=n(82654),c=n(60742),d=n(75093),p=n(64282);const u=(0,o.withSelect)(e=>({extensionsList:e("eventin/global").getExtensions()})),m=(0,i.compose)(u)(e=>{const{open:t,onCancel:n,extensionsList:i}=e,[u]=c.A.useForm(),{integrationLoading:m}=(0,o.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:g,invalidateResolution:_}=(0,o.useDispatch)("eventin/global"),v=Array.isArray(i)&&i?.find(e=>"whatsapp"===e.slug),{data:h={}}=v||{data:{}},{whatsapp_connected:f,whatsapp_token_hint:w,whatsapp_phone_number_id:E,whatsapp_business_account_id:y,whatsapp_admin_number:x}=h||{},b=async()=>{try{await u.validateFields()}catch(e){return}const e=u.getFieldsValue(),t=e.whatsapp_access_token?.trim();t?e.whatsapp_access_token=t:delete e.whatsapp_access_token;try{g({integrationLoading:!0}),await p.A.settings.updateSettings(e),u.setFieldValue("whatsapp_access_token",""),_("getExtensions"),(0,s.doAction)("eventin_notification",{type:"success",message:(0,l.__)("WhatsApp settings saved","wp-event-solution")}),n?.()}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{g({integrationLoading:!1})}};return(0,a.createElement)(d.xK,{open:t,onCancel:n,title:(0,l.__)("WhatsApp Configure","wp-event-solution"),onConnect:b,width:520,loading:m,form:u},(0,a.createElement)(r.A,{type:"info",showIcon:!0,style:{marginBottom:16},message:(0,l.__)("Get these values from your Meta app: WhatsApp → API Setup.","wp-event-solution")}),(0,a.createElement)(c.A,{form:u,layout:"vertical",onFinish:b,initialValues:{whatsapp_access_token:"",whatsapp_phone_number_id:E,whatsapp_business_account_id:y,whatsapp_admin_number:x}},(0,a.createElement)(d.h5,{label:(0,l.__)("Access Token","wp-event-solution"),name:"whatsapp_access_token",placeholder:w||(0,l.__)("Enter permanent access token","wp-event-solution"),tooltip:(0,l.__)("Leave empty to keep the saved token.","wp-event-solution"),type:"password",autoComplete:"new-password",rules:[{required:!f,message:(0,l.__)("Access Token is required","wp-event-solution")},{validator:(e,t)=>!t?.trim()||t.trim().length>=50?Promise.resolve():Promise.reject(new Error((0,l.__)('This does not look like a Meta access token. It should be a long text that starts with "EAA".',"wp-event-solution")))}]}),(0,a.createElement)(d.ks,{label:(0,l.__)("Phone Number ID","wp-event-solution"),name:"whatsapp_phone_number_id",autoComplete:"off",placeholder:(0,l.__)("e.g. 111222333444555","wp-event-solution"),tooltip:(0,l.__)("The ID of the WhatsApp number that sends the messages. It is not the phone number itself.","wp-event-solution"),rules:[{required:!0,message:(0,l.__)("Phone Number ID is required","wp-event-solution")}]}),(0,a.createElement)(d.ks,{label:(0,l.__)("WhatsApp Business Account ID","wp-event-solution"),name:"whatsapp_business_account_id",autoComplete:"off",placeholder:(0,l.__)("e.g. 999888777666555","wp-event-solution"),tooltip:(0,l.__)("Needed to load your approved message templates in the Automation builder.","wp-event-solution")}),(0,a.createElement)(d.ks,{label:(0,l.__)("Admin WhatsApp Number","wp-event-solution"),name:"whatsapp_admin_number",autoComplete:"off",placeholder:(0,l.__)("e.g. +8801712345678","wp-event-solution"),tooltip:(0,l.__)('Messages sent to "Admin Number" go here. Use the full number with country code.',"wp-event-solution")})))});n.d(t,["A",0,m])},1907(e,t,n){var a=n(51609),o=n(47143),i=n(27723),s=n(29491),l=n(52619),r=n(60742),c=n(75093),d=n(64282),p=n(54725);const u=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),m=(0,s.compose)(u)(e=>{const{open:t,onCancel:n,extensionsList:s}=e,[u]=r.A.useForm(),{integrationLoading:m}=(0,o.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:g,invalidateResolution:_}=(0,o.useDispatch)("eventin/global"),v=Array.isArray(s)&&s?.find(e=>"zoom"===e.slug),{data:h={}}=v||{data:{}},{zoom_redirect_url:f}=h||{},w=async()=>{try{const e=u.getFieldsValue();g({integrationLoading:!0});const t=await d.A.settings.updateSettings(e);_("getExtensions"),t.zoom_authorize_url&&(window.location.href=t.zoom_authorize_url)}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e.message})}finally{g({integrationLoading:!1})}};return(0,a.createElement)(c.xK,{open:t,onCancel:n,title:(0,i.__)("Zoom Configure","wp-event-solution"),onConnect:w,width:500,loading:m,form:u},(0,a.createElement)(r.A,{form:u,layout:"vertical",onFinish:w,initialValues:{zoom_client_id:h?.zoom_client_id,zoom_client_secret:h?.zoom_client_secret,zoom_redirect_url:h?.zoom_redirect_url}},(0,a.createElement)(c.h5,{label:(0,i.__)("Client ID","wp-event-solution"),name:"zoom_client_id",placeholder:(0,i.__)("Enter Client ID","wp-event-solution"),tooltip:(0,i.__)("Enter Client ID","wp-event-solution"),required:!0,rules:[{required:!0,message:(0,i.__)("Client ID is required","wp-event-solution")}]}),(0,a.createElement)(c.h5,{label:(0,i.__)("Client Secret Key","wp-event-solution"),name:"zoom_client_secret",placeholder:(0,i.__)("Enter Client Secret Key","wp-event-solution"),tooltip:(0,i.__)("Enter Client Secret Key","wp-event-solution"),required:!0,rules:[{required:!0,message:(0,i.__)("Client Secret Key is required","wp-event-solution")}]}),(0,a.createElement)(r.A.Item,{label:(0,i.__)("Redirect URL","wp-event-solution"),name:"zoom_redirect_url"},(0,a.createElement)(c.I3,{copyText:f||"",buttonTooltipText:(0,i.__)("Copy Redirect URL","wp-event-solution"),icon:(0,a.createElement)(p.d1Z,null),placeholder:(0,i.__)("Enter redirect url","wp-event-solution")}))))});n.d(t,["A",0,m])},59255(e,t,n){n.d(t,{U9:()=>p,ew:()=>c.e,ld:()=>u});var a=n(51609),o=n(27723),i=n(67313),s=n(64945),l=n(20710),r=n(22423),c=n(77908);const{Title:d}=i.A,p=["aisentic","migration-tool-for-eventin","eventin-addon-for-tutor-lms"],u=[{key:"1",label:(0,o.__)("Extensions","wp-event-solution"),icon:(0,a.createElement)(()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,a.createElement)("path",{d:"M7.5 2.25C7.5 1.42157 8.17157 0.75 9 0.75C9.82843 0.75 10.5 1.42157 10.5 2.25V3H12.75C13.5784 3 14.25 3.67157 14.25 4.5V6.75H15C15.8284 6.75 16.5 7.42157 16.5 8.25C16.5 9.07843 15.8284 9.75 15 9.75H14.25V12.75C14.25 13.5784 13.5784 14.25 12.75 14.25H10.5V15C10.5 15.8284 9.82843 16.5 9 16.5C8.17157 16.5 7.5 15.8284 7.5 15V14.25H5.25C4.42157 14.25 3.75 13.5784 3.75 12.75V9.75H3C2.17157 9.75 1.5 9.07843 1.5 8.25C1.5 7.42157 2.17157 6.75 3 6.75H3.75V4.5C3.75 3.67157 4.42157 3 5.25 3H7.5V2.25Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,a.createElement)(s.A,null)},{key:"2",label:(0,o.__)("Integrations","wp-event-solution"),icon:(0,a.createElement)(()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,a.createElement)("path",{d:"M6.75 3.75H4.5C3.67157 3.75 3 4.42157 3 5.25V13.5C3 14.3284 3.67157 15 4.5 15H13.5C14.3284 15 15 14.3284 15 13.5V11.25M11.25 3.75H14.25M14.25 3.75V6.75M14.25 3.75L8.25 9.75",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,a.createElement)(l.A,null)},{key:"3",label:(0,o.__)("Addons","wp-event-solution"),icon:(0,a.createElement)(()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,a.createElement)("path",{d:"M3 3.75H7.5V8.25H3V3.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,a.createElement)("path",{d:"M10.5 3.75H15V8.25H10.5V3.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,a.createElement)("path",{d:"M3 9.75H7.5V14.25H3V9.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,a.createElement)("path",{d:"M12.75 9.75V14.25M10.5 12H15",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,a.createElement)(r.A,null)}]},25280(e,t,n){n.d(t,{A:()=>i});var a=n(86087),o=n(19575);function i(e,t,n,i,s,l,r=!1){const[c,d]=(0,a.useState)(t),[p,u]=(0,a.useState)(!1),[m,g]=(0,a.useState)(!1);return(0,a.useEffect)(()=>{d(t)},[t]),{status:c,isLoading:p,isActive:r?"activate"===c:"off"!==c,buttonLoading:m,toggleModule:async t=>{let a={};if("function"==typeof l){const e=await l(t);if(!1===e)return;e&&"object"==typeof e&&(a=e)}u(!0);const c=r?"activate":"on",p=r?"deactivate":"off",m=await(0,o.A)(e,t?c:p,a);if(m){if(r)return void window.location.reload();d(t?"on":"off"),await n()}setTimeout(()=>!i&&u(!1),1500),m&&["eventin-addon-for-surecart","eventin-addon-for-fluentcart","rsvp","stripe","paypal","razorpay"].includes(e)&&s()},updateStatus:async t=>{g(!0);const a=await(0,o.A)(e,t);a&&r?window.location.reload():(a&&await n(),setTimeout(()=>!i&&g(!1),1500))}}}},25046(e,t,n){n.r(t);var a=n(51609),o=n(29491),i=n(47143),s=n(86087),l=n(27723),r=n(75093),c=n(49603),d=n(24581),p=n(4436),u=n(98739);const m=(0,i.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),g=(0,o.compose)(m)(function(e){const{extensions:t,isExtensionsLoading:n}=e,[o,i]=(0,s.useState)("1");return(0,a.createElement)(u.ff,{className:"eventin-page-wrapper"},(0,a.createElement)(d.A,{title:(0,l.__)("Extensions","wp-event-solution")}),(0,a.createElement)(p.A,{activeTab:o,setActiveTab:i,extensions:t,isExtensionsLoading:n}),(0,a.createElement)(c.A,null),(0,a.createElement)(r._W,null))});n.d(t,["default",0,g])},77908(e,t,n){n.d(t,["e",0,{ZOOM_CONFIG:"zoom",GOOGLE_MEET_CONFIG:"google_meet",EVENTIN_AI_CONFIG:"eventin_ai",GOOGLE_MAP_CONFIG:"google_map",TUTOR_LMS_CONFIG:"eventin-addon-for-tutor-lms",LEARNDASH_CONFIG:"eventin-addon-for-learndash",HUBSPOT_CONFIG:"hubspot",STRIPE_CONFIG:"stripe",PAYPAL_CONFIG:"paypal",WHATSAPP_CONFIG:"whatsapp",RAZORPAY_CONFIG:"razorpay"}])},98739(e,t,n){var a=n(27154),o=n(69815);const i=o.A.div`
	background-color: var(--etn-bg-page, #f4f6fa);
	padding: 12px 32px;
	min-height: 100vh;

	.addons-area-heading {
		width: 50%;
		margin-bottom: 30px;
		@media ( max-width: 768px ) {
			width: 100%;
		}
	}
`,s=o.A.div`
	background: var(--etn-surface, #fff);
	border-radius: 8px;
	margin-bottom: 16px;
	padding: 16px 24px;

	.etn-segment-nav {
		&.ant-segmented {
			background: var(--etn-bg-subtle, #f1f1f4);
			border-radius: 10px;
			padding: 4px;
			box-shadow: none;
		}

		.ant-segmented-item {
			border-radius: 7px;
			color: var(--etn-text-muted, #6b7280);
			font-size: 14px;
			font-weight: 500;
			transition: color 0.5s ease;

			&:hover:not( .ant-segmented-item-selected ) {
				color: var(--etn-primary-text, #7c3aed);
			}
		}

		.ant-segmented-item-selected {
			color: var(--etn-primary-text, #7c3aed);
			box-shadow: 0 1px 6px rgba( 0, 0, 0, 0.12 );
		}

		.ant-segmented-thumb {
			border-radius: 7px;
			background: var(--etn-surface, #fff);
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
`,l=o.A.div`
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
`,r=o.A.div`
	background: linear-gradient( 135deg, var(--etn-primary-bg, #faf5ff) 0%, var(--etn-surface, #fff) 60% );
	border: 1px solid var(--etn-primary-border, #ede9fe);
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
		color: var(--etn-text, #212327);
		font-weight: 600;
		margin: 0;
	}
	.etn-featured-description {
		font-size: 14px;
		color: var(--etn-text-muted, #6b7280);
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
`,c=o.A.div`
	background: var(--etn-surface, #fff);
	border-radius: 8px;
	margin-bottom: 30px;
	padding: 30px;
	@media ( max-width: 768px ) {
		padding: 20px;
	}
	.etn-extension-title {
		font-size: 20px;
		display: inline-block;
		color: var(--etn-text, #212327);
		font-weight: 600;
	}
	.etn-extension-description {
		font-size: 14px;
		color: var(--etn-text-muted, #6b7280);
		font-weight: 400;
	}
`,d=(e=>{const t=e.replace("#",""),n=parseInt(3===t.length?t.split("").map(e=>e+e).join(""):t,16);return`${n>>16&255}, ${n>>8&255}, ${255&n}`})(a.VG),p=o.A.div`
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

	background: var(--etn-surface, #fff);
	border-radius: 14px;
	margin: 0;
	height: 100%;
	padding-bottom: 22px;
	overflow: hidden;
	position: relative;
	border: 1px solid var(--etn-border-subtle, #ebeef3);
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
		background: var(--etn-bg-subtle, #f6f7fb);
		border: 1px solid var(--etn-border-subtle, #eef0f6);
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
		color: var(--etn-text, #1a1c21) !important;
	}

	.etn-card-desc {
		font-size: 13px;
		line-height: 1.6;
		color: var(--etn-text-muted, #6b7280);
		.etn-doc-link {
			color: var(--etn-primary-text, ${a.VG});
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
		color: var(--etn-text-muted, #6b7280);
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
		color: var(--etn-primary-text, ${a.VG});
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
			color: var(--etn-primary-text, ${a.VG});
			background: rgba( ${d}, 0.13 );
			border-color: rgba( ${d}, 0.28 );
		}
	}
	.etn-link-button {
		color: var(--etn-primary-text, ${a.VG});
		font-size: 15px;
		font-weight: 600;
		margin-top: 10px;
		text-decoration: underline;
		&:hover {
			text-decoration: underline;
			color: var(--etn-primary-text, ${a.VG});
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
		color: var(--etn-text-secondary, rgba( 0, 0, 0, 0.65 ));
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
`,u=o.A.div`
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
`,m=(o.A.span`
	font-size: 24px;
	margin-right: 10px;
`,{free:"background: var(--etn-success-bg, #dcfce7); color: var(--etn-success, #16a34a);",popular:"background: var(--etn-warning-bg, #fef9c3); color: var(--etn-warning, #a16207);",new:"background: var(--etn-info-bg, #dbeafe); color: var(--etn-info, #1d4ed8);",pro:"background: var(--etn-primary-bg, #f3e8ff); color: var(--etn-primary-text, #7c3aed);",trending:"background: var(--etn-warning-bg, #fff7ed); color: var(--etn-warning, #c2410c);",recommended:"background: var(--etn-success-bg, #ccfbf1); color: var(--etn-info, #0d9488);",featured:"background: var(--etn-warning-bg, #fef3c7); color: var(--etn-warning, #b45309);",updated:"background: var(--etn-info-bg, #cffafe); color: var(--etn-info, #0e7490);",default:"background: var(--etn-bg-subtle, #f3f4f6); color: var(--etn-text-secondary, #374151);"}),g=o.A.span`
	display: inline-flex;
	align-items: center;
	padding: 2px 9px;
	border-radius: 999px;
	font-size: 11px;
	font-weight: 600;
	line-height: 1.7;
	letter-spacing: 0.01em;
	white-space: nowrap;
	${({variant:e})=>m[e]||m.default}
`;o.A.div`
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
`,n.d(t,["GP",0,s,"Ij",0,g,"JS",0,l,"dQ",0,u,"ff",0,i,"i7",0,r,"nA",0,c,"vi",0,p])}}]);