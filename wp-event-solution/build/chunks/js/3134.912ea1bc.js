"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[3134,5092],{72124(e,t,n){n.d(t,{A:()=>p});var a=n(51609),i=n(86087),r=n(27723),o=n(50400),l=n(79888),s=n(90984),c=n(27579);const{TextArea:d}=l.A;function p({onSubmit:e,heading:t,subheading:n,placeholder:l,submitLabel:p,suggestions:m=[]}){const[u,g]=(0,i.useState)(""),h=(0,i.useRef)(),x=t=>{t.trim()&&(e(t),g(""))},v=null!=l?l:(0,r.__)("e.g. Create a two-day conference next month with early-bird tickets","eventin");return(0,a.createElement)(c.a_,null,(t||n)&&(0,a.createElement)(c.e3,null,t&&(0,a.createElement)(c.Vv,null,t),n&&(0,a.createElement)(c.fA,null,n)),(0,a.createElement)(c.DB,null,(0,a.createElement)(d,{ref:h,value:u,onChange:e=>g(e.target.value),onKeyDown:e=>{"Enter"===e.key&&(e.metaKey||e.ctrlKey)&&(e.preventDefault(),x(u))},rows:3,variant:"borderless",placeholder:v,"aria-label":v}),(0,a.createElement)(c.yC,null,(0,a.createElement)(c.G9,null,(0,r.__)("⌘ + Enter to submit","eventin")),(0,a.createElement)(o.A,{type:"primary",size:"large",icon:(0,a.createElement)(s.qx,null),iconPosition:"end",onClick:()=>x(u),disabled:!u.trim()},null!=p?p:(0,r.__)("Ask Eventin AI","eventin")))),m.length>0&&(0,a.createElement)(c.jR,null,m.map(e=>(0,a.createElement)(c.Vb,{key:e,type:"button",onClick:()=>(e=>{g(e);const t=h.current?.resizableTextArea?.textArea;t&&(t.focus(),window.requestAnimationFrame(()=>{t.setSelectionRange(e.length,e.length)}))})(e)},e))))}},92771(e,t,n){n.d(t,{A:()=>s});var a=n(51609),i=n(86087),r=n(72124),o=n(91397),l=n(45092);function s(e){const[t,n]=(0,i.useState)(""),[s,c]=(0,i.useState)(!1);return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(r.A,{...e,onSubmit:e=>{"ask"!==(0,o.$j)()?(n(e),c(!0)):(0,o.M4)(e)}}),(0,a.createElement)(l.default,{open:s,onClose:()=>c(!1),prompt:t}))}},29970(e,t,n){n.d(t,{dk:()=>a.A});var a=n(92771)},45092(e,t,n){n.r(t),n.d(t,{default:()=>x});var a=n(51609),i=n(86087),r=n(27723),o=n(82654),l=n(50400),s=n(38181),c=n(60742),d=n(79888),p=n(19549),m=n(64282),u=n(90984),g=n(91397),h=n(27579);function x({open:e,onClose:t,prompt:n}){const x=(0,g.T1)(),[v]=c.A.useForm(),[f,b]=(0,i.useState)(!1),[E,_]=(0,i.useState)(null),w=c.A.useWatch("consented",v),k=c.A.useWatch("email",v);return(0,i.useEffect)(()=>{e&&(v.resetFields(),_(null))},[e]),(0,a.createElement)(p.A,{open:e,onCancel:t,footer:null,width:468,centered:!0,maskClosable:!f,destroyOnHidden:!0},(0,a.createElement)(h.HM,null,(0,a.createElement)(h.wX,null,(0,a.createElement)(u.ZT,null)),(0,a.createElement)(h.L3,null,(0,r.__)("Turn on Eventin AI","eventin")),(0,a.createElement)(h.dc,null,(0,i.createInterpolateElement)((0,r.__)("Your assistant runs on the free <strong>Aisentic</strong> plugin. We will install and register it for you.","eventin"),{strong:(0,a.createElement)("strong",null)}))),(0,a.createElement)(h.QE,null,(0,a.createElement)(h.xE,null,(0,r.__)("150,000","eventin")),(0,a.createElement)(h.kq,null,(0,r.__)("free AI tokens · no card, no trial","eventin"))),(0,a.createElement)(h.Nt,null,(0,a.createElement)(c.A,{form:v,layout:"vertical",initialValues:{email:x.email,consented:!1},onFinish:async e=>{_(null),b(!0);try{await m.A.extensions.updateExtension({name:"aisentic",status:"activate",connect_account:!0,account_name:x.name,email:e.email.trim(),site_url:x.siteUrl}),(0,g.E4)(),(0,g.IL)(n),window.location.reload()}catch(e){_(e?.message||(0,r.__)("Installation failed. You can install Aisentic from Plugins → Add New instead.","eventin"))}finally{b(!1)}},requiredMark:!1},(0,a.createElement)(c.A.Item,{name:"email",label:(0,r.__)("Email","eventin"),rules:[{required:!0,type:"email",message:(0,r.__)("Enter a valid email address.","eventin")}]},(0,a.createElement)(d.A,{size:"large",disabled:f,placeholder:(0,r.__)("you@example.com","eventin")})),(0,a.createElement)(h.kE,null,(0,a.createElement)(c.A.Item,{name:"consented",valuePropName:"checked",noStyle:!0},(0,a.createElement)(s.A,{disabled:f},(0,r.__)("I agree to share my name, email & site address with Aisentic to create my free account.","eventin")))),E&&(0,a.createElement)(o.A,{type:"error",showIcon:!0,style:{marginTop:16},message:E}),(0,a.createElement)(h.ii,null,(0,a.createElement)(l.A,{size:"large",onClick:t,disabled:f},(0,r.__)("Not now","eventin")),(0,a.createElement)(l.A,{type:"primary",size:"large",htmlType:"submit",loading:f,disabled:!w||!k},f?(0,r.__)("Setting up…","eventin"):(0,r.__)("Install & get 150k tokens","eventin"))))))}},27579(e,t,n){var a=n(69815),i=n(27154);const r="#202223",o="#6D6D6D",l="#E5E7EB",s="#FFFFFF",c=a.A.h2`
	margin: 0;
	padding: 0;
	text-align: center;
	font-size: 40px;
	font-weight: 700;
	line-height: 1.15;
	letter-spacing: -0.5px;
	color: ${r};

	@media ( max-width: 768px ) {
		font-size: 30px;
	}
`,d=a.A.p`
	margin: 16px auto 0;
	max-width: 640px;
	text-align: center;
	font-size: 16px;
	line-height: 1.6;
	color: ${o};

	@media ( max-width: 768px ) {
		font-size: 15px;
	}
`,p=a.A.div`
	margin: 0 auto 40px;
	max-width: 720px;
`,m=a.A.div`
	margin: 0 auto;
	width: 100%;
	max-width: 860px;
`,u=a.A.div`
	border: 1px solid ${l};
	border-radius: 16px;
	background-color: ${s};
	padding: 20px 24px;
	box-shadow: 0 1px 2px rgba( 16, 16, 32, 0.04 );
	transition: border-color 0.2s ease;

	&:focus-within {
		border-color: ${i.VG};
	}

	/*
	 * The composer owns the frame, so the textarea inside is chrome-less.
	 *
	 * The !important flags are load-bearing: this renders inside WP admin's .wrap,
	 * whose textarea and textarea:focus rules (1px border plus a 1px box-shadow in
	 * link blue) outrank antd's borderless variant and draw a second box inside the
	 * card. Note for editors: no backticks in this comment — it lives inside an
	 * emotion template literal, and one would end the string.
	 */
	.ant-input {
		font-size: 15px;
		line-height: 1.6;
		padding: 0;
		resize: none;
		border: 0 !important;
		background: transparent !important;
		box-shadow: none !important;
		outline: none !important;

		&:hover,
		&:focus,
		&:focus-visible,
		&:focus-within {
			border: 0 !important;
			box-shadow: none !important;
			outline: none !important;
		}
	}
`,g=a.A.div`
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 12px;
	margin-top: 20px;
`,h=a.A.span`
	font-size: 13px;
	color: #9096a2;
`,x=a.A.div`
	display: flex;
	flex-wrap: wrap;
	justify-content: center;
	gap: 12px;
	margin-top: 24px;
`,v=a.A.button`
	cursor: pointer;
	border: 1px solid ${l};
	border-radius: 999px;
	background-color: ${s};
	padding: 9px 20px;
	font-size: 14px;
	color: #4b5162;
	box-shadow: 0 1px 2px rgba( 16, 16, 32, 0.03 );
	transition: color 0.2s ease, border-color 0.2s ease;

	&:hover {
		border-color: ${i.VG};
		color: ${i.VG};
	}
`,f=a.A.div`
	text-align: center;
`,b=a.A.div`
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 52px;
	height: 52px;
	border-radius: 16px;
	background-color: ${i.VG};
	color: #fff;
`,E=a.A.h3`
	margin: 16px 0 0;
	font-size: 22px;
	font-weight: 700;
	color: ${r};
`,_=a.A.p`
	margin: 8px 0 0;
	font-size: 14px;
	line-height: 1.5;
	color: ${o};

	strong {
		color: ${r};
		font-weight: 600;
	}
`,w=a.A.div`
	margin-top: 20px;
	border-radius: 12px;
	background-color: rgba( 107, 46, 229, 0.08 );
	padding: 20px 16px;
	text-align: center;
`,k=a.A.p`
	margin: 0;
	font-size: 30px;
	font-weight: 700;
	line-height: 1;
	color: ${i.VG};
`,A=a.A.p`
	margin: 8px 0 0;
	font-size: 13px;
	color: ${i.VG};
	opacity: 0.7;
`,y=a.A.div`
	margin-top: 20px;

	.ant-checkbox-checked .ant-checkbox-inner {
		background-color: ${i.VG};
		border-color: ${i.VG};
	}

	.ant-checkbox:hover .ant-checkbox-inner,
	.ant-checkbox-wrapper:hover .ant-checkbox-inner {
		border-color: ${i.VG};
	}
`,z=a.A.div`
	border-radius: 10px;
	background-color: rgba( 107, 46, 229, 0.06 );
	padding: 14px;

	.ant-checkbox-wrapper {
		align-items: flex-start;
		font-size: 13px;
		line-height: 1.5;
		color: #4b5162;
	}
`,V=a.A.div`
	display: flex;
	align-items: center;
	gap: 12px;
	margin-top: 24px;

	.ant-btn:last-of-type {
		flex: 1;
	}
`;n.d(t,["DB",0,u,"G9",0,h,"HM",0,f,"L3",0,E,"Nt",0,y,"QE",0,w,"Vb",0,v,"Vv",0,c,"a_",0,m,"dc",0,_,"e3",0,p,"fA",0,d,"ii",0,V,"jR",0,x,"kE",0,z,"kq",0,A,"wX",0,b,"xE",0,k,"yC",0,g])},96058(e,t,n){n.d(t,{A:()=>c});var a=n(51609),i=n(56427),r=n(27723),o=n(92911),l=n(18062),s=n(27154);function c(){return(0,a.createElement)(i.Fill,{name:s.PQ},(0,a.createElement)(o.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,a.createElement)(l.A,{title:(0,r.__)("Ask AI","eventin")})))}},59213(e,t,n){n.d(t,{A:()=>u});var a=n(51609),i=n(47143),r=n(27723),o=n(47767),l=n(90984),s=n(18939);const c=()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round",strokeLinejoin:"round","aria-hidden":"true"},(0,a.createElement)("rect",{x:"3",y:"5",width:"18",height:"16",rx:"2"}),(0,a.createElement)("path",{d:"M3 10h18"}),(0,a.createElement)("path",{d:"M8 3v4"}),(0,a.createElement)("path",{d:"M16 3v4"})),d=()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round",strokeLinejoin:"round","aria-hidden":"true"},(0,a.createElement)("path",{d:"M3 9V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 6v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-6z"}),(0,a.createElement)("path",{d:"M13 5v14"})),p=()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round",strokeLinejoin:"round","aria-hidden":"true"},(0,a.createElement)("path",{d:"M16 20v-1.5a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4V20"}),(0,a.createElement)("circle",{cx:"9.5",cy:"7.5",r:"3.5"}),(0,a.createElement)("path",{d:"M17 4.2a3.5 3.5 0 0 1 0 6.6"}),(0,a.createElement)("path",{d:"M21 20v-1.5a4 4 0 0 0-3-3.8"})),m=()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round",strokeLinejoin:"round","aria-hidden":"true"},(0,a.createElement)("circle",{cx:"12",cy:"12",r:"3"}),(0,a.createElement)("path",{d:"M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.9.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.9V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"}));function u(){const e=(0,o.Zp)(),t=(0,i.useSelect)(e=>e("eventin/global")?.getUserPermissions(),[]),n=!!window?.localized_data_obj?.evnetin_pro_active,u=t?.permissions,g=n&&!t?.is_super_admin&&Array.isArray(u),h=[{icon:c,permission:"etn_manage_event",title:(0,r.__)("Events","eventin"),description:(0,r.__)("Create single or recurring events with tickets, venues, categories & tags.","eventin"),action:(0,r.__)("Create an event","eventin"),path:"/events",badge:(0,r.__)("Start here","eventin")},{icon:d,permission:"etn_manage_order",title:(0,r.__)("Bookings","eventin"),description:(0,r.__)("Track ticket sales, orders, refunds and attendee check-ins in one place.","eventin"),action:(0,r.__)("View bookings","eventin"),path:"/bookings"},{icon:p,permission:"etn_manage_schedule",title:(0,r.__)("Schedules & Speakers","eventin"),description:(0,r.__)("Build multi-day agendas and assign speakers, organizers & sessions.","eventin"),action:(0,r.__)("Plan a schedule","eventin"),path:"/schedules"},{icon:m,permission:"etn_manage_setting",title:(0,r.__)("Settings & Guides","eventin"),description:(0,r.__)("Payments, emails, permalinks, integrations and how-to walkthroughs.","eventin"),action:(0,r.__)("Open settings","eventin"),path:"/settings"}].filter(({permission:e})=>!g||u.includes(e));return h.length?(0,a.createElement)(s.pV,null,h.map(({icon:t,title:n,description:i,action:r,path:o,badge:c})=>(0,a.createElement)(s.Zp,{key:n},(0,a.createElement)(s.OJ,null,(0,a.createElement)(s.AG,null,(0,a.createElement)(t,null)),c&&(0,a.createElement)(s.Sf,null,c)),(0,a.createElement)(s.ZB,null,n),(0,a.createElement)(s.c5,null,i),(0,a.createElement)(s.X9,{type:"button",onClick:()=>e(o)},r,(0,a.createElement)(l.fl,null))))):null}},13134(e,t,n){n.r(t),n.d(t,{default:()=>d});var a=n(51609),i=n(27723),r=n(29970),o=n(90984),l=n(96058),s=n(59213),c=n(18939);function d(){return(0,a.createElement)(c.ff,{className:"eventin-page-wrapper"},(0,a.createElement)(l.A,null),(0,a.createElement)(r.dk,{heading:(0,i.__)("Eventin AI assistant","eventin"),subheading:(0,i.__)("Describe the event you want to run and I will set up the event, tickets, schedule and speakers, then only ask for whatever is missing.","eventin"),suggestions:[(0,i.__)("How do I create a recurring event?","eventin"),(0,i.__)("How do I sell tickets with Stripe?","eventin"),(0,i.__)("How do I check in attendees?","eventin")]}),(0,a.createElement)(c.E0,null,(0,a.createElement)(c.eN,null,(0,a.createElement)(o.BZ,null),(0,i.__)("What I can do","eventin")),(0,a.createElement)(s.A,null)))}},18939(e,t,n){var a=n(69815),i=n(27154);const r=a.A.div`
	background-color: #f4f6fa;
	padding: 64px 32px 80px;
	min-height: 100vh;

	@media ( max-width: 768px ) {
		padding: 40px 20px 56px;
	}
`,o=a.A.div`
	margin-top: 64px;
`,l=a.A.div`
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	margin-bottom: 24px;
	font-size: 15px;
	font-weight: 500;
	color: #41454f;

	svg {
		color: ${i.VG};
	}
`,s=a.A.div`
	display: grid;
	grid-template-columns: repeat( auto-fit, minmax( 190px, 1fr ) );
	gap: 20px;
	margin: 0 auto;
	width: 100%;
	max-width: 900px;

	@media ( max-width: 600px ) {
		grid-template-columns: minmax( 0, 1fr );
	}
`,c=a.A.div`
	display: flex;
	flex-direction: column;
	border: 1px solid ${"#E5E7EB"};
	border-radius: 12px;
	background-color: #fff;
	padding: 20px;
	box-shadow: 0 1px 2px rgba( 16, 16, 32, 0.03 );
	transition: box-shadow 0.2s ease;

	&:hover {
		box-shadow: 0 8px 24px rgba( 16, 16, 32, 0.08 );
	}
`,d=a.A.div`
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	gap: 8px;
`,p=a.A.div`
	display: flex;
	align-items: center;
	justify-content: center;
	width: 40px;
	height: 40px;
	border-radius: 10px;
	background-color: rgba( 107, 46, 229, 0.08 );
	color: ${i.VG};
`,m=a.A.span`
	border-radius: 999px;
	background-color: rgba( 107, 46, 229, 0.08 );
	padding: 4px 10px;
	font-size: 10px;
	font-weight: 700;
	letter-spacing: 0.04em;
	text-transform: uppercase;
	color: ${i.VG};
`,u=a.A.p`
	margin: 16px 0 0;
	font-size: 17px;
	font-weight: 600;
	color: ${"#202223"};
`,g=a.A.p`
	margin: 8px 0 0;
	flex: 1;
	font-size: 13px;
	line-height: 1.6;
	color: ${"#6D6D6D"};
`,h=a.A.button`
	display: inline-flex;
	align-items: center;
	align-self: flex-start;
	gap: 6px;
	margin-top: 20px;
	padding: 0;
	border: 0;
	background: transparent;
	cursor: pointer;
	font-size: 13px;
	font-weight: 600;
	color: ${i.VG};

	&:hover {
		text-decoration: underline;
	}
`;n.d(t,["AG",0,p,"E0",0,o,"OJ",0,d,"Sf",0,m,"X9",0,h,"ZB",0,u,"Zp",0,c,"c5",0,g,"eN",0,l,"ff",0,r,"pV",0,s])}}]);