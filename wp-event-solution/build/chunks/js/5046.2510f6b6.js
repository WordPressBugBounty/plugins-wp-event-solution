"use strict";(globalThis.webpackChunkwp_event_solution=globalThis.webpackChunkwp_event_solution||[]).push([[5046],{11883(e,t,n){var i=n(51609),a=n(6836);n.d(t,["A",0,({height:e=22,width:t=22})=>(0,a.EZ)(()=>(({height:e,width:t})=>(0,i.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",fill:"none",viewBox:"0 0 20 20"},(0,i.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeLinejoin:"round",strokeWidth:"1.5",d:"M6.25 4.121h7.083c.69 0 1.25.56 1.25 1.25v1.25M12.5 10.788h-5M10 14.121H7.5"}),(0,i.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeWidth:"1.5",d:"M15.414 1.667H5.256c-.414 0-.837.06-1.172.306-1.062.78-1.88 2.517-.228 4.086.464.44 1.112.6 1.75.6h9.63c.662 0 1.847.095 1.847 2.114v6.211a3.34 3.34 0 0 1-3.33 3.35H6.226c-1.836 0-3.172-1.298-3.277-3.274L2.922 4.304"})))({height:e,width:t}))])},57584(e,t,n){n(51609),n(6836)},8358(e,t,n){n.d(t,{A:()=>c});var i=n(51609),a=n(27723),o=n(54725),l=n(58950),s=n(22451),r=n(27154);function c({children:e,...t}){return(0,i.createElement)(s.u,{type:l.rd.type,size:l.rd.size,variantstyle:l.rd.style,onClick:()=>window.open(r.aC,"_blank"),...t},(0,i.createElement)(o.tD6,null),e||(0,a.__)("Get Eventin Pro","eventin"))}},17026(e,t,n){var i=n(51609),a=n(77278),o=n(16370),l=n(47152),s=n(75063);n.d(t,["A",0,()=>(0,i.createElement)(l.A,{gutter:[16,16]},(0,i.createElement)(o.A,{xs:24,sm:24},(0,i.createElement)(s.A.Input,{active:!0,size:"large",style:{margin:"20px 0"}})),[...Array(6)].map((e,t)=>(0,i.createElement)(o.A,{xs:24,sm:12,md:8,key:t},(0,i.createElement)(a.A,{style:{borderRadius:8}},(0,i.createElement)(s.A.Avatar,{active:!0,size:"large",shape:"circle",style:{marginBottom:16,marginRight:16}}),(0,i.createElement)(s.A.Input,{style:{width:200,marginBottom:8},active:!0}),(0,i.createElement)(s.A.Input,{style:{width:120,marginBottom:8},active:!0}),(0,i.createElement)("div",{style:{display:"flex",gap:10,alignItems:"center",marginTop:16}},(0,i.createElement)(s.A.Button,{style:{width:100},active:!0}),(0,i.createElement)(s.A.Button,{style:{width:100},active:!0}))))))])},19575(e,t,n){var i=n(52619),a=n(27723),o=n(64282);n.d(t,["A",0,async(e,t,n={})=>{try{const a=await o.A.extensions.updateExtension({name:e,status:t,...n});return(0,i.doAction)("eventin_notification",{type:"success",message:a?.message}),!0}catch(e){return(0,i.doAction)("eventin_notification",{type:"error",message:e?.message||(0,a.__)("Update failed! Please check the plugin list and try again.","eventin")}),!1}}])},49603(e,t,n){var i=n(51609),a=n(47143),o=n(59255),l=n(85890),s=n(4629),r=n(44207),c=n(10229),d=n(16947),g=n(15875),m=n(1907);n.d(t,["A",0,()=>{const{modalType:e}=(0,a.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:t}=(0,a.useDispatch)("eventin/global"),n=()=>t({modalType:null,modalProps:null});return e&&{[o.ew.ZOOM_CONFIG]:(0,i.createElement)(m.A,{open:!0,onCancel:n}),[o.ew.GOOGLE_MEET_CONFIG]:(0,i.createElement)(r.A,{open:!0,onCancel:n}),[o.ew.EVENTIN_AI_CONFIG]:(0,i.createElement)(l.A,{open:!0,onCancel:n}),[o.ew.GOOGLE_MAP_CONFIG]:(0,i.createElement)(s.A,{open:!0,onCancel:n}),[o.ew.TUTOR_LMS_CONFIG]:(0,i.createElement)(g.A,{open:!0,onCancel:n}),[o.ew.STRIPE_CONFIG]:(0,i.createElement)(d.A,{open:!0,onCancel:n}),[o.ew.PAYPAL_CONFIG]:(0,i.createElement)(c.A,{open:!0,onCancel:n})}[e]||null}])},22423(e,t,n){var i=n(51609),a=n(29491),o=n(47143),l=n(86087),s=n(27723),r=n(16370),c=n(92911),d=n(47152),g=n(67313),m=n(6660);const{Title:u,Text:p}=g.A,_=(0,o.withDispatch)((e,t,{select:n})=>{const i=e("eventin/global");return{invalidateExtensions:()=>i.invalidateResolution("getExtensions"),invalidateSettings:()=>{i.invalidateResolution("getSettings"),n("eventin/global").getSettings()}}}),v=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),E=(0,a.compose)(v,_)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:a,invalidateSettings:o}=e||{},[g,_]=(0,l.useState)([]);return(0,l.useEffect)(()=>{t&&_(Array.isArray(t)&&t?.filter(e=>"addon"===e.type)||[])},[t]),(0,i.createElement)("div",{className:"etn-module-section"},(0,i.createElement)(d.A,{gutter:[30,30]},(0,i.createElement)(r.A,{span:24},(0,i.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,i.createElement)(u,{level:3,className:"etn-extension-title"},(0,s.__)("Addons","eventin")),(0,i.createElement)(p,{className:"etn-extension-description"}," ",(0,s.__)("Eventin addons","eventin")))),g.map(e=>(0,i.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(m.A,{module:e,invalidateExtensions:a,isExtensionsLoading:n,invalidateSettings:o})))))});n.d(t,["A",0,E])},85890(e,t,n){var i=n(51609),a=n(27723),o=n(29491),l=n(47143),s=n(52619),r=n(60742),c=n(75093),d=n(64282);const g=(0,l.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),m=(0,o.compose)(g)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[g]=r.A.useForm(),{integrationLoading:m}=(0,l.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:u,invalidateResolution:p}=(0,l.useDispatch)("eventin/global"),_=Array.isArray(o)&&o?.find(e=>"eventin_ai"===e.slug),{data:v={}}=_||{data:{}},{eventin_ai_auth_key:E}=v||{},f=async()=>{try{const e=g.getFieldsValue();u({integrationLoading:!0}),(await d.A.settings.updateSettings(e)).eventin_ai_auth_key&&(0,s.doAction)("eventin_notification",{type:"success",message:(0,a.__)("Open AI key updated successfully","eventin")}),p("getExtensions"),n()}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{u({integrationLoading:!1,modalType:null})}};return(0,i.createElement)(c.xK,{open:t,onCancel:n,title:(0,a.__)("Eventin AI Configure","eventin"),onConnect:f,width:500,loading:m,form:g},(0,i.createElement)(r.A,{form:g,layout:"vertical",onFinish:f,initialValues:{eventin_ai_auth_key:E}},(0,i.createElement)(c.h5,{label:(0,a.__)("Open AI Key","eventin"),name:"eventin_ai_auth_key",placeholder:(0,a.__)("Enter Open AI Key","eventin"),required:!0,type:"password",rules:[{required:!0,message:(0,a.__)("Open AI Key is required","eventin")}]})))});n.d(t,["A",0,m])},24581(e,t,n){n.d(t,{A:()=>r});var i=n(51609),a=n(56427),o=n(92911),l=n(18062),s=n(27154);function r(e){const{title:t}=e;return(0,i.createElement)(a.Fill,{name:s.PQ},(0,i.createElement)(o.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,i.createElement)(l.A,{title:t})))}},4436(e,t,n){var i=n(51609),a=n(86087),o=n(11804),l=n(17026),s=n(59255),r=n(98739);const c=s.ld.map(e=>({value:e.key,label:(0,i.createElement)("span",{className:"etn-segment-label"},e.icon,e.label)}));n.d(t,["A",0,function(e){const{activeTab:t,setActiveTab:n,extensions:d}=e||{},[g,m]=(0,a.useState)(!0);if((0,a.useEffect)(()=>{null!=d&&m(!1)},[d]),g)return(0,i.createElement)(l.A,null);const u=s.ld.find(e=>e.key===t)||s.ld[0];return(0,i.createElement)("div",{className:"etn-extensions-container"},(0,i.createElement)(r.GP,null,(0,i.createElement)(o.A,{value:t,onChange:e=>n(e),options:c,className:"etn-segment-nav"})),(0,i.createElement)(r.nA,null,(0,i.createElement)(r.JS,{key:t},u.children)))}])},4629(e,t,n){var i=n(51609),a=n(27723),o=n(29491),l=n(47143),s=n(52619),r=n(60742),c=n(75093),d=n(64282);const g=(0,l.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),m=(0,o.compose)(g)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[g]=r.A.useForm(),{integrationLoading:m}=(0,l.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:u,invalidateResolution:p}=(0,l.useDispatch)("eventin/global"),_=Array.isArray(o)&&o?.find(e=>"google_map"===e.slug),{data:v={}}=_||{data:{}},{google_api_key:E}=v||{},f=async()=>{try{const e=g.getFieldsValue();u({integrationLoading:!0}),(await d.A.settings.updateSettings(e)).google_api_key&&(0,s.doAction)("eventin_notification",{type:"success",message:(0,a.__)("Google Map API key updated successfully","eventin")}),p("getExtensions"),n()}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{u({integrationLoading:!1})}};return(0,i.createElement)(c.xK,{open:t,onCancel:n,title:(0,a.__)("Google Map Configure","eventin"),onConnect:f,width:500,loading:m,form:g},(0,i.createElement)(r.A,{form:g,layout:"vertical",onFinish:f,initialValues:{google_api_key:E}},(0,i.createElement)(c.h5,{label:(0,a.__)("Map API Key","eventin"),name:"google_api_key",placeholder:(0,a.__)("Enter Map API Key","eventin"),tooltip:(0,a.__)("Map API Key","eventin"),required:!0,rules:[{required:!0,message:(0,a.__)("Map API Key is required","eventin")}]})))});n.d(t,["A",0,m])},44207(e,t,n){var i=n(51609),a=n(47143),o=n(29491),l=n(52619),s=n(27723),r=n(60742),c=n(75093),d=n(64282),g=n(54725);const m=(0,a.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),u=(0,o.compose)(m)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[m]=r.A.useForm(),{integrationLoading:u}=(0,a.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:p,invalidateResolution:_}=(0,a.useDispatch)("eventin/global"),v=Array.isArray(o)&&o?.find(e=>"google_meet"===e.slug),{data:E={}}=v||{data:{}},{google_meet_client_id:f,google_meet_client_secret_key:x,google_meet_redirect_url:h}=E||{},y=async()=>{try{const e=m.getFieldsValue();p({integrationLoading:!0});const t=await d.A.settings.updateSettings(e);_("getExtensions"),t.google_meet_authorize_url&&(window.location.href=t.google_meet_authorize_url)}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e.message})}finally{p({integrationLoading:!1})}};return(0,i.createElement)(c.xK,{open:t,onCancel:n,title:(0,s.__)("Google Meet Configure","eventin"),onConnect:y,width:500,loading:u,form:m},(0,i.createElement)(r.A,{form:m,layout:"vertical",onFinish:y,initialValues:{google_meet_client_id:f,google_meet_client_secret_key:x,google_meet_redirect_url:h}},(0,i.createElement)(c.h5,{label:(0,s.__)("Client ID","eventin"),name:"google_meet_client_id",placeholder:(0,s.__)("Enter Client ID","eventin"),tooltip:(0,s.__)("Enter Client ID","eventin"),required:!0,type:"password",rules:[{required:!0,message:(0,s.__)("Client ID is required","eventin")}]}),(0,i.createElement)(c.h5,{label:(0,s.__)("Client Secret Key","eventin"),name:"google_meet_client_secret_key",placeholder:(0,s.__)("Enter Client Secret Key","eventin"),tooltip:(0,s.__)("Enter Client Secret Key","eventin"),required:!0,type:"password",rules:[{required:!0,message:(0,s.__)("Client Secret Key is required","eventin")}]}),(0,i.createElement)(r.A.Item,{label:(0,s.__)("Authorized Redirect URL","eventin"),name:"google_meet_redirect_url"},(0,i.createElement)(c.I3,{copyText:h,buttonTooltipText:(0,s.__)("Copy Redirect URL","eventin"),icon:(0,i.createElement)(g.d1Z,null),placeholder:(0,s.__)("Enter redirect url","eventin")}))))});n.d(t,["A",0,u])},20710(e,t,n){var i=n(51609),a=n(29491),o=n(47143),l=n(86087),s=n(27723),r=n(16370),c=n(92911),d=n(47152),g=n(67313),m=n(6660);const{Title:u,Text:p}=g.A,_=(0,o.withDispatch)(e=>{const t=e("eventin/global");return{invalidateExtensions:()=>t.invalidateResolution("getExtensions")}}),v=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),E=(0,a.compose)(v,_)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:a}=e||{},[o,g]=(0,l.useState)([]);return(0,l.useEffect)(()=>{t&&g(Array.isArray(t)&&t?.filter(e=>"integration"===e.type)||[])},[t]),(0,i.createElement)("div",{className:"etn-module-section"},(0,i.createElement)(d.A,{gutter:[30,30]},(0,i.createElement)(r.A,{span:24},(0,i.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,i.createElement)(u,{level:3,className:"etn-extension-title"},(0,s.__)("Integrations","eventin")),(0,i.createElement)(p,{className:"etn-extension-description"}," ",(0,s.__)("Third-party integrations","eventin")))),o.map(e=>(0,i.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(m.A,{module:e,invalidateExtensions:a,isExtensionsLoading:n})))))});n.d(t,["A",0,E])},64945(e,t,n){n.d(t,{A:()=>x});var i=n(51609),a=n(29491),o=n(47143),l=n(86087),s=n(27723),r=n(16370),c=n(92911),d=n(47152),g=n(67313),m=n(6660),u=n(59255),p=n(98739);const{Title:_,Text:v}=g.A,E=(0,o.withDispatch)((e,t,{select:n})=>{const i=e("eventin/global");return{invalidateExtensions:()=>i.invalidateResolution("getExtensions"),invalidateSettings:()=>{i.invalidateResolution("getSettings"),n("eventin/global").getSettings()}}}),f=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),x=(0,a.compose)(f,E)(e=>{const{extensions:t,isExtensionsLoading:n,invalidateExtensions:a,invalidateSettings:o}=e||{},[g,E]=(0,l.useState)([]),[f,x]=(0,l.useState)([]),[h,y]=(0,l.useState)([]),[b,A]=(0,l.useState)([]);return(0,l.useEffect)(()=>{if(t){E(Object.values(t).filter(e=>"module"===e.type)),x(Object.values(t).filter(e=>"addon"===e.type)),y(Object.values(t).filter(e=>"integration"===e.type));const e=Object.values(t);A(u.U9.map(t=>e.find(e=>e.slug===t||e.name===t)).filter(Boolean))}},[t]),(0,i.createElement)("div",{className:"etn-module-section"},b.length>0&&(0,i.createElement)(p.i7,null,(0,i.createElement)("div",{className:"etn-featured-heading"},(0,i.createElement)("span",{className:"etn-featured-ribbon"},(0,s.__)("Featured","eventin"))),(0,i.createElement)(d.A,{gutter:[30,30]},b.map(e=>(0,i.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(m.A,{module:e,invalidateExtensions:a,isExtensionsLoading:n,invalidateSettings:o}))))),(0,i.createElement)(d.A,{gutter:[30,30]},(0,i.createElement)(r.A,{span:24},(0,i.createElement)(c.A,{justify:"space-between",align:"center",gap:10},(0,i.createElement)(_,{level:3,className:"etn-extension-title"},(0,s.__)("Modules","eventin")),(0,i.createElement)(v,{className:"etn-extension-description"}," ",(0,s.__)("Eventin modules","eventin")))),g.map(e=>(0,i.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(m.A,{module:e,invalidateExtensions:a,isExtensionsLoading:n})))),(0,i.createElement)(d.A,{gutter:[30,30]},(0,i.createElement)(r.A,{span:24},(0,i.createElement)(c.A,{justify:"space-between",align:"center",gap:10,style:{marginTop:"30px"}},(0,i.createElement)(_,{level:3,className:"etn-extension-title"},(0,s.__)("Addons","eventin")),(0,i.createElement)(v,{className:"etn-extension-description"}," ",(0,s.__)("Eventin addons","eventin")))),f.map(e=>(0,i.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(m.A,{module:e,invalidateExtensions:a,isExtensionsLoading:n,invalidateSettings:o})))),(0,i.createElement)(d.A,{gutter:[30,30]},(0,i.createElement)(r.A,{span:24},(0,i.createElement)(c.A,{justify:"space-between",align:"center",gap:10,style:{marginTop:"30px"}},(0,i.createElement)(_,{level:3,className:"etn-extension-title"},(0,s.__)("Integrations","eventin")),(0,i.createElement)(v,{className:"etn-extension-description"}," ",(0,s.__)("Eventin integrations","eventin")))),h.map(e=>(0,i.createElement)(r.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(m.A,{module:e,invalidateExtensions:a,isExtensionsLoading:n})))))})},10229(e,t,n){var i=n(51609),a=n(47143),o=n(29491),l=n(52619),s=n(27723),r=n(38181),c=n(60742),d=n(75093),g=n(64282);const m=(0,a.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),u=(0,o.compose)(m)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[m]=c.A.useForm(),{integrationLoading:u}=(0,a.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:p,invalidateResolution:_}=(0,a.useDispatch)("eventin/global"),v=Array.isArray(o)&&o?.find(e=>"paypal"===e.slug),{data:E={}}=v||{data:{}},{paypal_client_id:f,paypal_client_secret:x,paypal_sandbox:h}=E||{},y=async()=>{try{const e=m.getFieldsValue();p({integrationLoading:!0}),await g.A.settings.updateSettings(e),_("getExtensions"),(0,l.doAction)("eventin_notification",{type:"success",message:(0,s.__)("PayPal settings saved","eventin")}),n?.()}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e.message})}finally{p({integrationLoading:!1})}};return(0,i.createElement)(d.xK,{open:t,onCancel:n,title:(0,s.__)("PayPal Configure","eventin"),onConnect:y,width:500,loading:u,form:m},(0,i.createElement)(c.A,{form:m,layout:"vertical",onFinish:y,initialValues:{paypal_client_id:f,paypal_client_secret:x,paypal_sandbox:h}},(0,i.createElement)(d.h5,{label:(0,s.__)("PayPal Client ID","eventin"),name:"paypal_client_id",placeholder:(0,s.__)("Enter client id","eventin"),required:!0,rules:[{required:!0,message:(0,s.__)("Client ID is required","eventin")}]}),(0,i.createElement)(d.h5,{label:(0,s.__)("PayPal Secret Key","eventin"),name:"paypal_client_secret",placeholder:(0,s.__)("Enter secret key","eventin"),required:!0,rules:[{required:!0,message:(0,s.__)("Secret key is required","eventin")}]}),(0,i.createElement)(c.A.Item,{name:"paypal_sandbox",valuePropName:"checked"},(0,i.createElement)(r.A,null,(0,s.__)("Test payments with PayPal sandbox","eventin")))))});n.d(t,["A",0,u])},37762(e,t,n){var i=n(51609),a=n(27723),o=n(67313),l=n(11883);const{Text:s}=o.A,r=()=>(0,i.createElement)("svg",{width:"16",height:"16",viewBox:"0 0 16 16",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("circle",{cx:"8",cy:"8",r:"7",stroke:"currentColor",strokeWidth:"1.5"}),(0,i.createElement)("path",{d:"M6.5 5.5L11 8L6.5 10.5V5.5Z",fill:"currentColor"})),c={display:"inline-flex",alignItems:"center",gap:"6px",fontSize:"14px",fontWeight:"500",color:"#4b5563",textDecoration:"none",cursor:"pointer",transition:"color 0.2s ease"},d=({href:e,children:t})=>(0,i.createElement)("a",{href:e,target:"_blank",rel:"noreferrer",style:c,onMouseEnter:e=>e.currentTarget.style.color="#1677ff",onMouseLeave:e=>e.currentTarget.style.color="#4b5563"},t);n.d(t,["v",0,({description:e,notice:t,doc_link:n,video_link:o})=>(0,i.createElement)("div",{className:"etn-card-desc",style:{marginBottom:"20px"}},(0,i.createElement)(s,null,e.length>90?e.slice(0,90).concat("..."):e),t&&(0,i.createElement)(s,{style:{display:"flex",color:"#ff7129",marginTop:"10px"}},t),(0,i.createElement)("div",{style:{display:"flex",alignItems:"center",gap:"16px",marginTop:"14px"}},(0,i.createElement)(d,{href:n},(0,i.createElement)(l.A,null)," ",(0,a.__)("Read More","eventin")),o&&(0,i.createElement)(d,{href:o},(0,i.createElement)(r,null)," ",(0,a.__)("Tutorial","eventin"))))])},32066(e,t,n){var i=n(51609),a=n(86087),o=n(27723),l=n(47143),s=n(50400),r=n(92911),c=n(7638),d=n(64282),g=n(52619),m=n(8358),u=n(77908);const p=[u.e.TUTOR_LMS_CONFIG],_=new Set(Object.values(u.e)),v={fontSize:"16px",padding:"20px"},E={backgroundColor:"#F3F4F6"},f="zoom";n.d(t,["x",0,e=>{const{type:t,isProActive:n,is_pro:u,deps:x,loading:h,data:y}=e,{setEventinState:b}=(0,l.useDispatch)("eventin/global"),{actions:A,btnStyle:w}=(({status:e,type:t,slug:n,onChangeStatus:i,upgrade_link:l,settings_link:s,deps:r,upgrade:d,setEventinState:g})=>{const m=(0,a.useCallback)(()=>{g({modalType:n})},[n,g]);if("integration"===t&&r?.length>0)return"off"===e?{actions:[],btnStyle:v}:{actions:{install:[{label:(0,o.__)("Activate","eventin"),variant:c.zB,onClick:()=>i("activate")}],activate:[{label:(0,o.__)("Deactivate","eventin"),variant:c.Vt,onClick:()=>i("deactivate")}]}[e]||[{label:(0,o.__)("Install","eventin"),variant:c.zB,onClick:()=>i("install")}],btnStyle:v};const u={on:()=>"integration"===t||"eventin_ai"===n?_.has(n)?[{label:(0,o.__)("Configure","eventin"),variant:c.Vt,style:E,onClick:m}]:[]:r?.length||"addon"===t?[{label:(0,o.__)("Install","eventin"),variant:c.zB,onClick:()=>i("install")}]:[],install:()=>[{label:(0,o.__)("Activate","eventin"),variant:c.zB,onClick:()=>i("activate")}],upgrade:()=>[{label:(0,o.__)("Download","eventin"),variant:c.zB,href:l,target:"_blank"}],activate:()=>{const e=p.includes(n)?{label:(0,o.__)("Configure","eventin"),variant:c.Vt,onClick:m}:s&&{label:(0,o.__)("Configure","eventin"),variant:c.Vt,href:s,target:"_blank"};return[{label:(0,o.__)("Deactivate","eventin"),variant:c.Vt,onClick:()=>i("deactivate")},e].filter(Boolean)}};return{actions:u[e]?.()||[],btnStyle:v}})({...e,setEventinState:b}),[k,C]=(0,a.useState)(!1),[S,L]=(0,a.useState)({zoom_connected:"yes"===y?.zoom_connected,google_meet_connected:"yes"===y?.google_meet_connected}),I=(0,a.useCallback)(async e=>{try{C(!0),await d.A.settings.updateSettings(e)&&(L(t=>({...t,zoom_connected:e?.zoom_connected,google_meet_connected:e?.google_meet_connected})),(0,g.doAction)("eventin_notification",{type:"success",message:(0,o.__)("Disconnected successfully","eventin")}))}catch(e){(0,g.doAction)("eventin_notification",{type:"error",message:e.message})}finally{C(!1)}},[]),z=(0,a.useCallback)((e,n,a)=>"integration"===t&&n?(0,i.createElement)(s.A,{style:w,variant:e===f?c.Vt:c.zB,onClick:()=>I(a),loading:k,disabled:k},(0,o.__)("Disconnect","eventin")):null,[t,w,I,k]);return(0,a.useMemo)(()=>{if(!n&&u)return(0,i.createElement)(m.A,{sx:{height:"36px",fontSize:"14px"}});if("module"===t&&!x?.length)return null;if(!A.length)return null;const e=z(f,S?.zoom_connected,{zoom_token:{},zoom_connected:!1}),a=z("google_meet",S?.google_meet_connected,{google_token:{},google_meet_connected:!1});return(0,i.createElement)(r.A,{gap:20,wrap:"wrap"},e,a,A.map((e,t)=>(0,i.createElement)(s.A,{key:t,...e,style:{...w,...e.style||{}},loading:h},e.label)))},[A,w,x,h,n,u,t,S,z])}])},70334(e,t,n){var i=n(51609),a=n(43960);n.d(t,["j",0,({checked:e,loading:t,disabled:n,onChange:o})=>(0,i.createElement)(a.A,{className:"etn-addon-module-switch",loading:t,checked:e,onChange:o,disabled:n})])},6660(e,t,n){var i=n(51609),a=n(27723),o=n(19549),l=n(67313),s=(n(57584),n(64282)),r=n(25280),c=n(98739),d=n(37762),g=n(32066),m=n(70334);const{Title:u}=l.A,p=["stripe","paypal"],_=e=>{if(null==e)return!1;if("boolean"==typeof e)return e;if("number"==typeof e)return 0!==e;if("string"==typeof e){const t=e.trim().toLowerCase();return""!==t&&"0"!==t&&"false"!==t}return!!e};n.d(t,["A",0,({module:e,invalidateExtensions:t,isExtensionsLoading:n,invalidateSettings:l})=>{const{name:v,title:E,description:f,status:x,notice:h,icon:y,settings_link:b,doc_link:A,video_link:w,is_pro:k,upgrade_link:C,upgrade:S,deps:L,type:I,slug:z,badge_tags:T=[],data:N={}}=e||{},F=p.includes(z)?async e=>{if(!e)return{};let t=null;try{t=await s.A.settings.getSettings()}catch(e){t=null}const n=(e=>{if(!e)return[];const t=[];return"woocommerce"===e.sell_tickets&&t.push("WooCommerce"),_(e.surecart_status)&&t.push("SureCart"),_(e.fluentcart_status)&&t.push("FluentCart"),t})(t);return 0===n.length?{}:new Promise(e=>{o.A.confirm({title:(0,a.__)("Disable other payment methods?","eventin"),content:n.join(", ")+" "+(0,a.__)("is currently enabled. Enabling this payment method will disable it. Continue?","eventin"),okText:(0,a.__)("Continue","eventin"),cancelText:(0,a.__)("Cancel","eventin"),onOk:()=>e({clear_conflicts:!0}),onCancel:()=>e(!1)})})}:void 0,{status:V,isLoading:M,buttonLoading:O,isActive:P,toggleModule:R,updateStatus:G}=(0,r.A)(v,x,t,n,l,F),j=!!window.localized_data_obj.evnetin_pro_active;return(0,i.createElement)(c.vi,null,(0,i.createElement)("div",{className:"etn-module-card-header"},(0,i.createElement)("div",{className:"etn-module-card-header-icon",dangerouslySetInnerHTML:{__html:y}}),!(!j&&k)&&(0,i.createElement)(m.j,{checked:P,onChange:R,loading:M})),(0,i.createElement)("div",{className:"etn-module-card-body"},(0,i.createElement)("div",{style:{display:"flex",alignItems:"center",flexWrap:"wrap",gap:"8px",margin:"0 0 10px 0"}},(0,i.createElement)(u,{level:4,style:{margin:0,fontSize:"20px"}},E),T.map(e=>(0,i.createElement)(c.Ij,{key:e,variant:e.toLowerCase()},e))),(0,i.createElement)(d.v,{description:f,notice:h,doc_link:A,video_link:w})),(0,i.createElement)(c.dQ,{isFooter:!0},(0,i.createElement)(g.x,{status:x,loading:O,onChangeStatus:G,upgrade:S,upgrade_link:C,settings_link:b,type:I,slug:z,deps:L,is_pro:k,isProActive:j,data:N})))}])},16947(e,t,n){var i=n(51609),a=n(47143),o=n(29491),l=n(52619),s=n(27723),r=n(60742),c=n(75093),d=n(64282);const g=(0,a.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),m=(0,o.compose)(g)(e=>{const{open:t,onCancel:n,extensionsList:o}=e,[g]=r.A.useForm(),{integrationLoading:m}=(0,a.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:u,invalidateResolution:p}=(0,a.useDispatch)("eventin/global"),_=Array.isArray(o)&&o?.find(e=>"stripe"===e.slug),{data:v={}}=_||{data:{}},{stripe_live_publishable_key:E,stripe_live_secret_key:f,etn_stripe_webhook_secret:x}=v||{},h=async()=>{try{const e=g.getFieldsValue();u({integrationLoading:!0}),await d.A.settings.updateSettings(e),p("getExtensions"),(0,l.doAction)("eventin_notification",{type:"success",message:(0,s.__)("Stripe settings saved","eventin")}),n?.()}catch(e){(0,l.doAction)("eventin_notification",{type:"error",message:e.message})}finally{u({integrationLoading:!1})}};return(0,i.createElement)(c.xK,{open:t,onCancel:n,title:(0,s.__)("Stripe Configure","eventin"),onConnect:h,width:500,loading:m,form:g},(0,i.createElement)(r.A,{form:g,layout:"vertical",onFinish:h,initialValues:{stripe_live_publishable_key:E,stripe_live_secret_key:f,etn_stripe_webhook_secret:x}},(0,i.createElement)(c.h5,{label:(0,s.__)("Publishable Key","eventin"),name:"stripe_live_publishable_key",placeholder:(0,s.__)("Enter publishable key","eventin"),required:!0,rules:[{required:!0,message:(0,s.__)("Publishable key is required","eventin")}]}),(0,i.createElement)(c.h5,{label:(0,s.__)("Secret Key","eventin"),name:"stripe_live_secret_key",placeholder:(0,s.__)("Enter secret key","eventin"),required:!0,rules:[{required:!0,message:(0,s.__)("Secret key is required","eventin")}]}),(0,i.createElement)(c.h5,{label:(0,s.__)("Webhook Signing Secret","eventin"),name:"etn_stripe_webhook_secret",placeholder:(0,s.__)("Enter webhook signing secret (whsec_...)","eventin")})))});n.d(t,["A",0,m])},15875(e,t,n){var i=n(51609),a=n(47143),o=n(86087),l=n(27723),s=n(52619),r=n(69815),c=n(38181),d=n(52741),g=n(92911),m=n(60742),u=n(51643),p=n(75093),_=n(7638),v=n(64282);const E=r.A.div`
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
`,f={enroll_mode:"all_attendees",auto_create_users:!0,send_credentials:!0,unenroll_on_refund:!0},x=e=>"on"===e||!0===e;n.d(t,["A",0,({open:e,onCancel:t})=>{const[n]=m.A.useForm(),[r,h]=(0,o.useState)(f),[y,b]=(0,o.useState)(!1),[A,w]=(0,o.useState)(!0),{integrationLoading:k}=(0,a.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:C}=(0,a.useDispatch)("eventin/global");(0,o.useEffect)(()=>{let e=!0;return(async()=>{try{const t=await v.A.tutorLms.getSettings();if(!e)return;const i={enroll_mode:t?.enroll_mode||f.enroll_mode,auto_create_users:x(t?.auto_create_users),send_credentials:x(t?.send_credentials),unenroll_on_refund:x(t?.unenroll_on_refund)};h(i),n.setFieldsValue(i)}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e?.message||(0,l.__)("Failed to load settings","eventin")})}finally{e&&w(!1)}})(),()=>{e=!1}},[n]);const S=async()=>{try{const e=await n.validateFields();b(!0),C({integrationLoading:!0});const i={enroll_mode:e.enroll_mode,auto_create_users:e.auto_create_users?"on":"off",send_credentials:e.send_credentials?"on":"off",unenroll_on_refund:e.unenroll_on_refund?"on":"off"};await v.A.tutorLms.updateSettings(i),(0,s.doAction)("eventin_notification",{type:"success",message:(0,l.__)("Tutor LMS settings saved","eventin")}),t()}catch(e){if(e?.errorFields)return;(0,s.doAction)("eventin_notification",{type:"error",message:e?.message||(0,l.__)("Failed to save settings","eventin")})}finally{b(!1),C({integrationLoading:!1})}},L=(0,i.createElement)(g.A,{align:"center",justify:"flex-end",gap:10},(0,i.createElement)(_.Ay,{variant:_.zB,onClick:S,loading:y||k,type:"submit"},(0,l.__)("Save Changes","eventin")));return(0,i.createElement)(p.xK,{open:e,onCancel:t,title:(0,l.__)("Tutor LMS Integration for Eventin","eventin"),width:580,loading:y||A,footerContent:L,form:n},(0,i.createElement)(E,null,(0,i.createElement)(m.A,{form:n,layout:"horizontal",labelCol:{span:14},wrapperCol:{span:10},labelAlign:"left",colon:!1,onFinish:S,initialValues:r},(0,i.createElement)(m.A.Item,{label:(0,l.__)("Who gets enrolled?","eventin"),name:"enroll_mode",layout:"vertical",labelCol:{span:24},wrapperCol:{span:24}},(0,i.createElement)(u.Ay.Group,null,(0,i.createElement)(g.A,{vertical:!0,gap:8},(0,i.createElement)(u.Ay,{value:"purchaser"},(0,l.__)("Purchaser only","eventin")),(0,i.createElement)(u.Ay,{value:"all_attendees"},(0,l.__)("Every registered attendee","eventin"))))),(0,i.createElement)(d.A,{style:{marginBlock:"5px"}}),(0,i.createElement)(m.A.Item,{name:"auto_create_users",valuePropName:"checked"},(0,i.createElement)(c.A,{className:"etn-checkbox-label"},(0,l.__)("Create WP accounts for guest attendees","eventin"))),(0,i.createElement)(m.A.Item,{name:"send_credentials",valuePropName:"checked"},(0,i.createElement)(c.A,{className:"etn-checkbox-label"},(0,l.__)("Email login credentials to new users","eventin"))),(0,i.createElement)(m.A.Item,{name:"unenroll_on_refund",valuePropName:"checked"},(0,i.createElement)(c.A,{className:"etn-checkbox-label"},(0,l.__)("Unenroll on refund / cancellation / failure","eventin"))))))}])},1907(e,t,n){var i=n(51609),a=n(47143),o=n(27723),l=n(29491),s=n(52619),r=n(60742),c=n(75093),d=n(64282),g=n(54725);const m=(0,a.withSelect)(e=>{const t=e("eventin/global");return{extensionsList:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),u=(0,l.compose)(m)(e=>{const{open:t,onCancel:n,extensionsList:l}=e,[m]=r.A.useForm(),{integrationLoading:u}=(0,a.useSelect)(e=>e("eventin/global").getEventinState(),[]),{setEventinState:p,invalidateResolution:_}=(0,a.useDispatch)("eventin/global"),v=Array.isArray(l)&&l?.find(e=>"zoom"===e.slug),{data:E={}}=v||{data:{}},{zoom_redirect_url:f}=E||{},x=async()=>{try{const e=m.getFieldsValue();p({integrationLoading:!0});const t=await d.A.settings.updateSettings(e);_("getExtensions"),t.zoom_authorize_url&&(window.location.href=t.zoom_authorize_url)}catch(e){(0,s.doAction)("eventin_notification",{type:"error",message:e.message})}finally{p({integrationLoading:!1})}};return(0,i.createElement)(c.xK,{open:t,onCancel:n,title:(0,o.__)("Zoom Configure","eventin"),onConnect:x,width:500,loading:u,form:m},(0,i.createElement)(r.A,{form:m,layout:"vertical",onFinish:x,initialValues:{zoom_client_id:E?.zoom_client_id,zoom_client_secret:E?.zoom_client_secret,zoom_redirect_url:E?.zoom_redirect_url}},(0,i.createElement)(c.h5,{label:(0,o.__)("Client ID","eventin"),name:"zoom_client_id",placeholder:(0,o.__)("Enter Client ID","eventin"),tooltip:(0,o.__)("Enter Client ID","eventin"),required:!0,rules:[{required:!0,message:(0,o.__)("Client ID is required","eventin")}]}),(0,i.createElement)(c.h5,{label:(0,o.__)("Client Secret Key","eventin"),name:"zoom_client_secret",placeholder:(0,o.__)("Enter Client Secret Key","eventin"),tooltip:(0,o.__)("Enter Client Secret Key","eventin"),required:!0,rules:[{required:!0,message:(0,o.__)("Client Secret Key is required","eventin")}]}),(0,i.createElement)(r.A.Item,{label:(0,o.__)("Redirect URL","eventin"),name:"zoom_redirect_url"},(0,i.createElement)(c.I3,{copyText:f||"",buttonTooltipText:(0,o.__)("Copy Redirect URL","eventin"),icon:(0,i.createElement)(g.d1Z,null),placeholder:(0,o.__)("Enter redirect url","eventin")}))))});n.d(t,["A",0,u])},59255(e,t,n){n.d(t,{U9:()=>g,ew:()=>c.e,ld:()=>m});var i=n(51609),a=n(27723),o=n(67313),l=n(64945),s=n(20710),r=n(22423),c=n(77908);const{Title:d}=o.A,g=["aisentic","migration-tool-for-eventin","eventin-addon-for-tutor-lms"],m=[{key:"1",label:(0,a.__)("Extensions","eventin"),icon:(0,i.createElement)(()=>(0,i.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("path",{d:"M7.5 2.25C7.5 1.42157 8.17157 0.75 9 0.75C9.82843 0.75 10.5 1.42157 10.5 2.25V3H12.75C13.5784 3 14.25 3.67157 14.25 4.5V6.75H15C15.8284 6.75 16.5 7.42157 16.5 8.25C16.5 9.07843 15.8284 9.75 15 9.75H14.25V12.75C14.25 13.5784 13.5784 14.25 12.75 14.25H10.5V15C10.5 15.8284 9.82843 16.5 9 16.5C8.17157 16.5 7.5 15.8284 7.5 15V14.25H5.25C4.42157 14.25 3.75 13.5784 3.75 12.75V9.75H3C2.17157 9.75 1.5 9.07843 1.5 8.25C1.5 7.42157 2.17157 6.75 3 6.75H3.75V4.5C3.75 3.67157 4.42157 3 5.25 3H7.5V2.25Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,i.createElement)(l.A,null)},{key:"2",label:(0,a.__)("Integrations","eventin"),icon:(0,i.createElement)(()=>(0,i.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("path",{d:"M6.75 3.75H4.5C3.67157 3.75 3 4.42157 3 5.25V13.5C3 14.3284 3.67157 15 4.5 15H13.5C14.3284 15 15 14.3284 15 13.5V11.25M11.25 3.75H14.25M14.25 3.75V6.75M14.25 3.75L8.25 9.75",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,i.createElement)(s.A,null)},{key:"3",label:(0,a.__)("Addons","eventin"),icon:(0,i.createElement)(()=>(0,i.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 18 18",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("path",{d:"M3 3.75H7.5V8.25H3V3.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,i.createElement)("path",{d:"M10.5 3.75H15V8.25H10.5V3.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,i.createElement)("path",{d:"M3 9.75H7.5V14.25H3V9.75Z",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}),(0,i.createElement)("path",{d:"M12.75 9.75V14.25M10.5 12H15",stroke:"currentColor",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"})),null),children:(0,i.createElement)(r.A,null)}]},25280(e,t,n){n.d(t,{A:()=>o});var i=n(86087),a=n(19575);function o(e,t,n,o,l,s){const[r,c]=(0,i.useState)(t),[d,g]=(0,i.useState)(!1),[m,u]=(0,i.useState)(!1);return{status:r,isLoading:d,isActive:"off"!==r,buttonLoading:m,toggleModule:async t=>{let i={};if("function"==typeof s){const e=await s(t);if(!1===e)return;e&&"object"==typeof e&&(i=e)}g(!0);const r=await(0,a.A)(e,t?"on":"off",i);r&&(c(t?"on":"off"),await n()),setTimeout(()=>!o&&g(!1),1500),r&&["eventin-addon-for-surecart","eventin-addon-for-fluentcart","rsvp","stripe","paypal"].includes(e)&&l()},updateStatus:async t=>{u(!0),await(0,a.A)(e,t)&&await n(),setTimeout(()=>!o&&u(!1),1500)}}}},25046(e,t,n){n.r(t);var i=n(51609),a=n(29491),o=n(47143),l=n(86087),s=n(27723),r=n(75093),c=n(49603),d=n(24581),g=n(4436),m=n(98739);const u=(0,o.withSelect)(e=>{const t=e("eventin/global");return{extensions:t.getExtensions(),isExtensionsLoading:t.isResolving("getExtensions")}}),p=(0,a.compose)(u)(function(e){const{extensions:t,isExtensionsLoading:n}=e,[a,o]=(0,l.useState)("1");return(0,i.createElement)(m.ff,{className:"eventin-page-wrapper"},(0,i.createElement)(d.A,{title:(0,s.__)("Extensions","eventin")}),(0,i.createElement)(g.A,{activeTab:a,setActiveTab:o,extensions:t,isExtensionsLoading:n}),(0,i.createElement)(c.A,null),(0,i.createElement)(r._W,null))});n.d(t,["default",0,p])},77908(e,t,n){n.d(t,["e",0,{ZOOM_CONFIG:"zoom",GOOGLE_MEET_CONFIG:"google_meet",EVENTIN_AI_CONFIG:"eventin_ai",GOOGLE_MAP_CONFIG:"google_map",TUTOR_LMS_CONFIG:"eventin-addon-for-tutor-lms",STRIPE_CONFIG:"stripe",PAYPAL_CONFIG:"paypal"}])},98739(e,t,n){var i=n(27154),a=n(69815);const o=a.A.div`
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
`,l=a.A.div`
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
`,s=a.A.div`
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
`,r=a.A.div`
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
`,c=a.A.div`
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
`,d=a.A.div`
	border-radius: 8px;
	margin: 0;
	min-height: 350px;
	overflow: hidden;
	position: relative;
	border: 1px solid #d9d9d9;
	display: flex;
	flex-direction: column;
	justify-content: space-between;

	.etn-module-card-header {
		padding: 20px;
		display: flex;
		justify-content: space-between;
		gap: 20px;
		@media ( max-width: 768px ) {
			flex-wrap: wrap;
		}
	}
	.etn-module-card-header-icon {
		display: flex;
		align-items: center;
		justify-content: center;
		border-radius: 8px;
	}
	.etn-module-card-body,
	.etn-module-card-footer {
		padding: 0 20px;
	}

	.etn-card-desc {
		font-size: 14px;
		color: #838790;
		.etn-doc-link {
			color: ${i.VG};
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
	.etn-link-button {
		color: ${i.VG};
		font-size: 15px;
		font-weight: 600;
		margin-top: 10px;
		text-decoration: underline;
		&:hover {
			text-decoration: underline;
			color: ${i.VG};
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
`,g=a.A.div`
	height: ${({isFooter:e})=>e?"70px":"0px"};
	display: flex;
	justify-content: flex-end;
	align-items: center;
	gap: 16px;
	padding-inline: 20px;
`,m=(a.A.span`
	font-size: 24px;
	margin-right: 10px;
`,{free:"background: #dcfce7; color: #16a34a;",popular:"background: #fef9c3; color: #a16207;",new:"background: #dbeafe; color: #1d4ed8;",pro:"background: #f3e8ff; color: #7c3aed;",trending:"background: #fff7ed; color: #c2410c;",recommended:"background: #ccfbf1; color: #0d9488;",featured:"background: #fef3c7; color: #b45309;",updated:"background: #cffafe; color: #0e7490;",default:"background: #f3f4f6; color: #374151;"}),u=a.A.span`
	display: inline-flex;
	align-items: center;
	padding: 2px 10px;
	border-radius: 4px;
	font-size: 12px;
	font-weight: 500;
	line-height: 1.6;
	${({variant:e})=>m[e]||m.default}
`;a.A.div`
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
`,n.d(t,["GP",0,l,"Ij",0,u,"JS",0,s,"dQ",0,g,"ff",0,o,"i7",0,r,"nA",0,c,"vi",0,d])}}]);