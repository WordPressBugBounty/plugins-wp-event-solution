"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[6244],{38799(e,t,n){var a=n(51609),r=n(16370),i=n(92911),o=n(47152),l=n(84976),s=n(27723),c=n(54725),d=n(7638),m=n(57237),p=n(91556),g=n(59317),u=n(62515),v=n(27154);n.d(t,["A",0,()=>{const{evnetin_ai_active:e,evnetin_pro_active:t}=window.eventin_ai_local_data||window?.localized_multivendor_data?.eventin_ai||{},n=window?.localized_multivendor_data?.is_vendor||!1,{doAction:f}=wp.hooks;return(0,a.createElement)(g.GI,{className:"wrapper"},(0,a.createElement)(o.A,{className:"intro",gutter:60,align:"middle"},(0,a.createElement)(r.A,{xs:24,sm:24,md:24,lg:12},(0,a.createElement)(u.A,null)),(0,a.createElement)(r.A,{xs:24,sm:24,md:24,lg:12,style:{paddingInline:"0px"}},(0,a.createElement)(i.A,{vertical:!0,style:{padding:"0 20px"}},(0,a.createElement)(m.A,{className:"intro-title",level:2},(0,s.__)("Build dynamic events & memorable experiences","wp-event-solution")),(0,a.createElement)("ul",{className:"intro-list"},(0,a.createElement)(p.A,{text:(0,s.__)("Define Speaker/Organizer roles and profiles.","wp-event-solution")}),(0,a.createElement)(p.A,{text:(0,s.__)("Set ticket tiers, pricing models, and availability.","wp-event-solution")}),(0,a.createElement)(p.A,{text:(0,s.__)("Craft a visually appealing landing page to promote your event.","wp-event-solution")}),(0,a.createElement)(p.A,{text:(0,s.__)("Configure RSVP options and manage attendee confirmation flow.","wp-event-solution")})),(0,a.createElement)(i.A,{className:"intro-actions",justify:"start",align:"center",wrap:!0,gap:12},!n&&(0,a.createElement)(g.WO,{onClick:()=>{f(e&&t?"eventin-ai-create-event-modal-visible":"eventin-ai-text-generator-modal",{visible:!0})}},(0,a.createElement)(c.J8Z,null),(0,a.createElement)(g.oY,null,(0,s.__)("Create an event with AI","wp-event-solution"))),(0,a.createElement)(l.N_,{to:"/events/create/basic"},(0,a.createElement)(d.Ay,{variant:d.zB,className:"intro-button"},(0,a.createElement)(c.bW0,{width:18,height:18}),(0,s.__)("Creating new event","wp-event-solution"))),(0,a.createElement)(d.Ay,{variant:d.Vt,className:"intro-button",onClick:()=>{window.open(v.GP,"_blank")}},(0,s.__)("Learn more","wp-event-solution"),(0,a.createElement)(c.A1_,{width:16,height:16})))))))}])},16244(e,t,n){n.r(t);var a=n(51609),r=n(92911),i=n(47767),o=n(56427),l=n(29491),s=n(47143),c=n(52619),d=n(86087),m=n(75093),p=n(18062),g=n(27154),u=n(38799);const v=(0,s.withSelect)(e=>{const t=e("eventin/global");return{totalEvents:t.getTotalEvents(),isLoading:t.isResolving("getTotalEvents")}}),f=(0,l.compose)(v)(function(e){const{totalEvents:t,isLoading:n}=e,l=(0,i.Zp)(),{pathname:s}=(0,i.zy)();(0,d.useLayoutEffect)(()=>{!n&&t>0&&l("/events",{replace:!0})},[t,n]);const v=(0,c.applyFilters)("eventin-ai-create-event-modal","eventin-ai");return(0,a.createElement)(a.Fragment,null,(0,a.createElement)(o.Fill,{name:g.PQ},(0,a.createElement)(r.A,{justify:"space-between",align:"center"},(0,a.createElement)(p.A,{title:"Events"}))),(0,a.createElement)(u.A,null),(0,a.createElement)(v,{navigate:l,pathname:s}),(0,a.createElement)(m._W,null))});n.d(t,["default",0,f])},91556(e,t,n){var a=n(51609),r=n(69815);n(27723);const i=r.A.li`
	position: relative;
	padding: 0 0 0 24px;

	&::before {
		content: '';
		position: absolute;
		top: 50%;
		left: 8px;
		width: 4px;
		height: 4px;
		background-color: rgba( 0, 0, 0, 0.6 );
		border-radius: 50%;
		transform: translateY( -50% );
	}
`;n.d(t,["A",0,({text:e})=>(0,a.createElement)(i,null,e)])},59317(e,t,n){var a=n(69815),r=n(27154);const i=a.A.div`
	background-color: var(--etn-surface, #ffffff);
	max-width: 1224px;
	margin: 40px auto;
	padding: 0 20px;

	.intro-title {
		font-weight: 600;
		font-size: 2rem;
		line-height: 38px;
		margin: 0 0 20px;
		color: var(--etn-text, #020617);
	}

	.intro-list {
		display: flex;
		flex-direction: column;
		align-items: flex-start;
		font-size: 1rem;
		gap: 8px;
		margin: 0 0 2rem;
		padding: 0;
		color: var(--etn-text, #020617);
		list-style: none;
		font-weight: 400;
	}
	.intro-button {
		display: flex;
		align-items: center;
		border-radius: 6px;
	}
`,o=a.A.div`
	margin: 0;
	position: relative;

	@media screen and ( max-width: 768px ) {
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
		transform: translate( -50%, -50% );
		border-radius: 50%;
		background-color: rgba( 255, 255, 255, 0.2 );
		color: #fff;
		width: 60px !important;
		height: 60px;
		border-color: var(--etn-primary-border, #f0eafc);

		&:hover {
			background-color: ${r.VG};
			color: #fff;
			border-color: transparent;
		}

		&:focus {
			outline: none;
		}
	}
`,l=a.A.button`
	display: flex;
	align-items: center;
	gap: 8px;
	padding: 8px 16px;
	font-size: 16px;
	font-weight: 500;
	background: var(--etn-primary-bg, #f9f5ff);
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
`,s=a.A.span`
	background: linear-gradient(
		90deg,
		#fc8327 0%,
		#e83aa5 50.5%,
		#3a4ff2 100%
	);
	-webkit-background-clip: text;
	-webkit-text-fill-color: rgba( 0, 0, 0, 0 );
	background-clip: text;
`;n.d(t,["GI",0,i,"VT",0,o,"WO",0,l,"oY",0,s])},62515(e,t,n){var a=n(51609),r=n(86087),i=n(54725),o=n(7638),l=n(6836),s=n(59317);n.d(t,["A",0,()=>{const[e,t]=(0,r.useState)(!1),n=(0,l.QF)("/images/events/video-cover.webp");return(0,a.createElement)(s.VT,null,e?(0,a.createElement)("iframe",{"aria-label":"demo-video",width:"100%",height:"372.5",src:"https://www.youtube.com/embed/dhSwZ3p02v0?si=lNY2_iFYzU0zFva0?autoplay=1",allow:"accelerometer; autoplay",allowFullScreen:!0}):(0,a.createElement)(a.Fragment,null,(0,a.createElement)("img",{src:n,alt:"Eventin intro video"}),(0,a.createElement)(o.Ay,{variant:o.zB,icon:(0,a.createElement)(i.vUL,null),size:"large",className:"video-play-button",onClick:()=>t(!0)})))}])}}]);