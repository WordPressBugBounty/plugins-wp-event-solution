"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[3134,5092],{72124(e,t,n){n.d(t,{A:()=>d});var a=n(51609),r=n(86087),o=n(27723),i=n(50400),l=n(79888),s=n(90984),c=n(27579);const{TextArea:p}=l.A;function d({onSubmit:e,heading:t,subheading:n,placeholder:l,submitLabel:d,suggestions:u=[]}){const[m,g]=(0,r.useState)(""),x=(0,r.useRef)(),h=t=>{t.trim()&&(e(t),g(""))},v=null!=l?l:(0,o.__)("e.g. Create a two-day conference next month with early-bird tickets","wp-event-solution");return(0,a.createElement)(c.a_,null,(t||n)&&(0,a.createElement)(c.e3,null,t&&(0,a.createElement)(c.Vv,null,t),n&&(0,a.createElement)(c.fA,null,n)),(0,a.createElement)(c.DB,null,(0,a.createElement)(p,{ref:x,value:m,onChange:e=>g(e.target.value),onKeyDown:e=>{"Enter"===e.key&&(e.metaKey||e.ctrlKey)&&(e.preventDefault(),h(m))},rows:3,variant:"borderless",placeholder:v,"aria-label":v}),(0,a.createElement)(c.yC,null,(0,a.createElement)(c.G9,null,(0,o.__)("⌘ + Enter to submit","wp-event-solution")),(0,a.createElement)(i.A,{type:"primary",size:"large",icon:(0,a.createElement)(s.qx,null),iconPosition:"end",onClick:()=>h(m),disabled:!m.trim()},null!=d?d:(0,o.__)("Ask Eventin AI","wp-event-solution")))),u.length>0&&(0,a.createElement)(c.jR,null,u.map(e=>(0,a.createElement)(c.Vb,{key:e,type:"button",onClick:()=>(e=>{g(e);const t=x.current?.resizableTextArea?.textArea;t&&(t.focus(),window.requestAnimationFrame(()=>{t.setSelectionRange(e.length,e.length)}))})(e)},e))))}},92771(e,t,n){n.d(t,{A:()=>s});var a=n(51609),r=n(86087),o=n(72124),i=n(91397),l=n(45092);function s(e){const[t,n]=(0,r.useState)(""),[s,c]=(0,r.useState)(!1);return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(o.A,{...e,onSubmit:e=>{"ask"!==(0,i.$j)()?(n(e),c(!0)):(0,i.M4)(e)}}),(0,a.createElement)(l.default,{open:s,onClose:()=>c(!1),prompt:t}))}},29970(e,t,n){n.d(t,{dk:()=>a.A});var a=n(92771)},45092(e,t,n){n.r(t),n.d(t,{default:()=>h});var a=n(51609),r=n(86087),o=n(27723),i=n(82654),l=n(50400),s=n(38181),c=n(60742),p=n(79888),d=n(19549),u=n(64282),m=n(90984),g=n(91397),x=n(27579);function h({open:e,onClose:t,prompt:n}){const h=(0,g.T1)(),[v]=c.A.useForm(),[w,f]=(0,r.useState)(!1),[b,E]=(0,r.useState)(null),_=c.A.useWatch("consented",v),k=c.A.useWatch("email",v);return(0,r.useEffect)(()=>{e&&(v.resetFields(),E(null))},[e]),(0,a.createElement)(d.A,{open:e,onCancel:t,footer:null,width:468,centered:!0,maskClosable:!w,destroyOnHidden:!0},(0,a.createElement)(x.HM,null,(0,a.createElement)(x.wX,null,(0,a.createElement)(m.ZT,null)),(0,a.createElement)(x.L3,null,(0,o.__)("Turn on Eventin AI","wp-event-solution")),(0,a.createElement)(x.dc,null,(0,r.createInterpolateElement)((0,o.__)("Your assistant runs on the free <strong>Aisentic</strong> plugin. We will install and register it for you.","wp-event-solution"),{strong:(0,a.createElement)("strong",null)}))),(0,a.createElement)(x.QE,null,(0,a.createElement)(x.xE,null,(0,o.__)("2,000","wp-event-solution")),(0,a.createElement)(x.kq,null,(0,o.__)("free AI credits · no card, no trial","wp-event-solution"))),(0,a.createElement)(x.Nt,null,(0,a.createElement)(c.A,{form:v,layout:"vertical",initialValues:{email:h.email,consented:!1},onFinish:async e=>{E(null),f(!0);try{await u.A.extensions.updateExtension({name:"aisentic",status:"activate",connect_account:!0,account_name:h.name,email:e.email.trim(),site_url:h.siteUrl}),(0,g.E4)(),(0,g.IL)(n),window.location.reload()}catch(e){E(e?.message||(0,o.__)("Installation failed. You can install Aisentic from Plugins → Add New instead.","wp-event-solution"))}finally{f(!1)}},requiredMark:!1},(0,a.createElement)(c.A.Item,{name:"email",label:(0,o.__)("Email","wp-event-solution"),rules:[{required:!0,type:"email",message:(0,o.__)("Enter a valid email address.","wp-event-solution")}]},(0,a.createElement)(p.A,{size:"large",disabled:w,placeholder:(0,o.__)("you@example.com","wp-event-solution")})),(0,a.createElement)(x.kE,null,(0,a.createElement)(c.A.Item,{name:"consented",valuePropName:"checked",noStyle:!0},(0,a.createElement)(s.A,{disabled:w},(0,o.__)("I agree to share my name, email & site address with Aisentic to create my free account.","wp-event-solution")))),b&&(0,a.createElement)(i.A,{type:"error",showIcon:!0,style:{marginTop:16},message:b}),(0,a.createElement)(x.ii,null,(0,a.createElement)(l.A,{size:"large",onClick:t,disabled:w},(0,o.__)("Not now","wp-event-solution")),(0,a.createElement)(l.A,{type:"primary",size:"large",htmlType:"submit",loading:w,disabled:!_||!k},w?(0,o.__)("Setting up…","wp-event-solution"):(0,o.__)("Install & get 2,000 credits","wp-event-solution"))))))}},27579(e,t,n){var a=n(69815),r=n(27154);const o="var(--etn-text, #202223)",i="var(--etn-text-muted, #6D6D6D)",l="var(--etn-border-subtle, #E5E7EB)",s="var(--etn-surface, #FFFFFF)",c=a.A.h2`
	margin: 0;
	padding: 0;
	text-align: center;
	font-size: 40px;
	font-weight: 700;
	line-height: 1.15;
	letter-spacing: -0.5px;
	color: ${o};

	@media ( max-width: 768px ) {
		font-size: 30px;
	}
`,p=a.A.p`
	margin: 16px auto 0;
	max-width: 640px;
	text-align: center;
	font-size: 16px;
	line-height: 1.6;
	color: ${i};

	@media ( max-width: 768px ) {
		font-size: 15px;
	}
`,d=a.A.div`
	margin: 0 auto 40px;
	max-width: 720px;
`,u=a.A.div`
	margin: 0 auto;
	width: 100%;
	max-width: 860px;
`,m=a.A.div`
	border: 1px solid ${l};
	border-radius: 16px;
	background-color: ${s};
	padding: 20px 24px;
	box-shadow: 0 1px 2px rgba( 16, 16, 32, 0.04 );
	transition: border-color 0.2s ease;

	&:focus-within {
		border-color: ${r.VG};
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
`,x=a.A.span`
	font-size: 13px;
	color: var(--etn-text-muted, #9096a2);
`,h=a.A.div`
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
	color: var(--etn-text-secondary, #4b5162);
	box-shadow: 0 1px 2px rgba( 16, 16, 32, 0.03 );
	transition: color 0.2s ease, border-color 0.2s ease;

	&:hover {
		border-color: ${r.VG};
		color: var(--etn-primary-text, ${r.VG});
	}
`,w=a.A.div`
	text-align: center;
`,f=a.A.div`
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 52px;
	height: 52px;
	border-radius: 16px;
	background-color: ${r.VG};
	color: #fff;
`,b=a.A.h3`
	margin: 16px 0 0;
	font-size: 22px;
	font-weight: 700;
	color: ${o};
`,E=a.A.p`
	margin: 8px 0 0;
	font-size: 14px;
	line-height: 1.5;
	color: ${i};

	strong {
		color: ${o};
		font-weight: 600;
	}
`,_=a.A.div`
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
	color: var(--etn-primary-text, ${r.VG});
`,A=a.A.p`
	margin: 8px 0 0;
	font-size: 13px;
	color: var(--etn-primary-text, ${r.VG});
	opacity: 0.7;
`,y=a.A.div`
	margin-top: 20px;

	.ant-checkbox-checked .ant-checkbox-inner {
		background-color: ${r.VG};
		border-color: ${r.VG};
	}

	.ant-checkbox:hover .ant-checkbox-inner,
	.ant-checkbox-wrapper:hover .ant-checkbox-inner {
		border-color: ${r.VG};
	}
`,z=a.A.div`
	border-radius: 10px;
	background-color: rgba( 107, 46, 229, 0.06 );
	padding: 14px;

	.ant-checkbox-wrapper {
		align-items: flex-start;
		font-size: 13px;
		line-height: 1.5;
		color: var(--etn-text-secondary, #4b5162);
	}
`,V=a.A.div`
	display: flex;
	align-items: center;
	gap: 12px;
	margin-top: 24px;

	.ant-btn:last-of-type {
		flex: 1;
	}
`;n.d(t,["DB",0,m,"G9",0,x,"HM",0,w,"L3",0,b,"Nt",0,y,"QE",0,_,"Vb",0,v,"Vv",0,c,"a_",0,u,"dc",0,E,"e3",0,d,"fA",0,p,"ii",0,V,"jR",0,h,"kE",0,z,"kq",0,A,"wX",0,f,"xE",0,k,"yC",0,g])},96058(e,t,n){n.d(t,{A:()=>c});var a=n(51609),r=n(56427),o=n(27723),i=n(92911),l=n(18062),s=n(27154);function c(){return(0,a.createElement)(r.Fill,{name:s.PQ},(0,a.createElement)(i.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,a.createElement)(l.A,{title:(0,o.__)("Ask AI","wp-event-solution")})))}},59213(e,t,n){n.d(t,{A:()=>m});var a=n(51609),r=n(47143),o=n(27723),i=n(47767),l=n(90984),s=n(18939);const c=()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round",strokeLinejoin:"round","aria-hidden":"true"},(0,a.createElement)("rect",{x:"3",y:"5",width:"18",height:"16",rx:"2"}),(0,a.createElement)("path",{d:"M3 10h18"}),(0,a.createElement)("path",{d:"M8 3v4"}),(0,a.createElement)("path",{d:"M16 3v4"})),p=()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round",strokeLinejoin:"round","aria-hidden":"true"},(0,a.createElement)("path",{d:"M3 9V7a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2a2 2 0 0 0 0 6v2a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-6z"}),(0,a.createElement)("path",{d:"M13 5v14"})),d=()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round",strokeLinejoin:"round","aria-hidden":"true"},(0,a.createElement)("path",{d:"M16 20v-1.5a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4V20"}),(0,a.createElement)("circle",{cx:"9.5",cy:"7.5",r:"3.5"}),(0,a.createElement)("path",{d:"M17 4.2a3.5 3.5 0 0 1 0 6.6"}),(0,a.createElement)("path",{d:"M21 20v-1.5a4 4 0 0 0-3-3.8"})),u=()=>(0,a.createElement)("svg",{width:"18",height:"18",viewBox:"0 0 24 24",fill:"none",stroke:"currentColor",strokeWidth:"1.8",strokeLinecap:"round",strokeLinejoin:"round","aria-hidden":"true"},(0,a.createElement)("circle",{cx:"12",cy:"12",r:"3"}),(0,a.createElement)("path",{d:"M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.9.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.9 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.9l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.9.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.9-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.9V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"}));function m(){const e=(0,i.Zp)(),t=(0,r.useSelect)(e=>e("eventin/global")?.getUserPermissions(),[]),n=!!window?.localized_data_obj?.evnetin_pro_active,m=t?.permissions,g=n&&!t?.is_super_admin&&Array.isArray(m),x=[{icon:c,permission:"etn_manage_event",title:(0,o.__)("Events","wp-event-solution"),description:(0,o.__)("Create single or recurring events with tickets, venues, categories & tags.","wp-event-solution"),action:(0,o.__)("Create an event","wp-event-solution"),path:"/events",badge:(0,o.__)("Start here","wp-event-solution")},{icon:p,permission:"etn_manage_order",title:(0,o.__)("Bookings","wp-event-solution"),description:(0,o.__)("Track ticket sales, orders, refunds and attendee check-ins in one place.","wp-event-solution"),action:(0,o.__)("View bookings","wp-event-solution"),path:"/bookings"},{icon:d,permission:"etn_manage_schedule",title:(0,o.__)("Schedules & Speakers","wp-event-solution"),description:(0,o.__)("Build multi-day agendas and assign speakers, organizers & sessions.","wp-event-solution"),action:(0,o.__)("Plan a schedule","wp-event-solution"),path:"/schedules"},{icon:u,permission:"etn_manage_setting",title:(0,o.__)("Settings & Guides","wp-event-solution"),description:(0,o.__)("Payments, emails, permalinks, integrations and how-to walkthroughs.","wp-event-solution"),action:(0,o.__)("Open settings","wp-event-solution"),path:"/settings"}].filter(({permission:e})=>!g||m.includes(e));return x.length?(0,a.createElement)(s.pV,null,x.map(({icon:t,title:n,description:r,action:o,path:i,badge:c})=>(0,a.createElement)(s.Zp,{key:n},(0,a.createElement)(s.OJ,null,(0,a.createElement)(s.AG,null,(0,a.createElement)(t,null)),c&&(0,a.createElement)(s.Sf,null,c)),(0,a.createElement)(s.ZB,null,n),(0,a.createElement)(s.c5,null,r),(0,a.createElement)(s.X9,{type:"button",onClick:()=>e(i)},o,(0,a.createElement)(l.fl,null))))):null}},13134(e,t,n){n.r(t),n.d(t,{default:()=>p});var a=n(51609),r=n(27723),o=n(29970),i=n(90984),l=n(96058),s=n(59213),c=n(18939);function p(){return(0,a.createElement)(c.ff,{className:"eventin-page-wrapper"},(0,a.createElement)(l.A,null),(0,a.createElement)(o.dk,{heading:(0,r.__)("Eventin AI assistant","wp-event-solution"),subheading:(0,r.__)("Describe the event you want to run and I will set up the event, tickets, schedule and speakers, then only ask for whatever is missing.","wp-event-solution"),suggestions:[(0,r.__)("How do I create a recurring event?","wp-event-solution"),(0,r.__)("How do I sell tickets with Stripe?","wp-event-solution"),(0,r.__)("How do I check in attendees?","wp-event-solution")]}),(0,a.createElement)(c.E0,null,(0,a.createElement)(c.eN,null,(0,a.createElement)(i.BZ,null),(0,r.__)("What I can do","wp-event-solution")),(0,a.createElement)(s.A,null)))}},18939(e,t,n){var a=n(69815),r=n(27154);const o=a.A.div`
	background-color: var(--etn-bg-page, #f4f6fa);
	padding: 64px 32px 80px;
	min-height: 100vh;

	@media ( max-width: 768px ) {
		padding: 40px 20px 56px;
	}
`,i=a.A.div`
	margin-top: 64px;
`,l=a.A.div`
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	margin-bottom: 24px;
	font-size: 15px;
	font-weight: 500;
	color: var(--etn-text-secondary, #41454f);

	svg {
		color: var(--etn-primary-text, ${r.VG});
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
	border: 1px solid ${"var(--etn-border-subtle, #E5E7EB)"};
	border-radius: 12px;
	background-color: var(--etn-surface, #fff);
	padding: 20px;
	box-shadow: 0 1px 2px rgba( 16, 16, 32, 0.03 );
	transition: box-shadow 0.2s ease;

	&:hover {
		box-shadow: 0 8px 24px rgba( 16, 16, 32, 0.08 );
	}
`,p=a.A.div`
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	gap: 8px;
`,d=a.A.div`
	display: flex;
	align-items: center;
	justify-content: center;
	width: 40px;
	height: 40px;
	border-radius: 10px;
	background-color: rgba( 107, 46, 229, 0.08 );
	color: var(--etn-primary-text, ${r.VG});
`,u=a.A.span`
	border-radius: 999px;
	background-color: rgba( 107, 46, 229, 0.08 );
	padding: 4px 10px;
	font-size: 10px;
	font-weight: 700;
	letter-spacing: 0.04em;
	text-transform: uppercase;
	color: var(--etn-primary-text, ${r.VG});
`,m=a.A.p`
	margin: 16px 0 0;
	font-size: 17px;
	font-weight: 600;
	color: ${"var(--etn-text, #202223)"};
`,g=a.A.p`
	margin: 8px 0 0;
	flex: 1;
	font-size: 13px;
	line-height: 1.6;
	color: ${"var(--etn-text-muted, #6D6D6D)"};
`,x=a.A.button`
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
	color: var(--etn-primary-text, ${r.VG});

	&:hover {
		text-decoration: underline;
	}
`;n.d(t,["AG",0,d,"E0",0,i,"OJ",0,p,"Sf",0,u,"X9",0,x,"ZB",0,m,"Zp",0,c,"c5",0,g,"eN",0,l,"ff",0,o,"pV",0,s])}}]);