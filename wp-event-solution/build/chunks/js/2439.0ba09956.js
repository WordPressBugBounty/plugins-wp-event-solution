"use strict";(globalThis.webpackChunkwp_event_solution=globalThis.webpackChunkwp_event_solution||[]).push([[2439],{45048(e,t,n){var a=n(51609),r=n(16370),l=n(92911),o=n(47152),i=n(84976),s=n(27723),c=n(7638),m=n(57237),d=n(39251),u=n(1704),p=n(16162);n.d(t,["A",0,()=>(0,a.createElement)(u.G,{className:"wrapper"},(0,a.createElement)(o.A,{className:"intro",gutter:60,align:"middle"},(0,a.createElement)(r.A,{xs:24,sm:24,md:24,lg:12},(0,a.createElement)(p.A,null)),(0,a.createElement)(r.A,{xs:24,sm:24,md:24,lg:12},(0,a.createElement)(l.A,{vertical:!0},(0,a.createElement)(m.A,{className:"intro-title",level:2,sx:{color:"#0C274A"}},(0,s.__)("Bring your sessions to life with interactive attendees","eventin")),(0,a.createElement)("ul",{className:"intro-list"},(0,a.createElement)(d.A,{text:(0,s.__)("Keep your meetings on track and boost your productivity","eventin")}),(0,a.createElement)(d.A,{text:(0,s.__)("Save attendees as templates & use them time & again","eventin")}),(0,a.createElement)(d.A,{text:(0,s.__)("Create and manage your personal attendees from here","eventin")})),(0,a.createElement)(l.A,{className:"intro-actions",justify:"start",align:"center",gap:12},(0,a.createElement)(i.N_,{to:"/attendees/create"},(0,a.createElement)(c.Ay,{variant:c.zB,className:"intro-button"},(0,s.__)("Let's Start Creating","eventin"))))))))])},2439(e,t,n){n.r(t);var a=n(51609),r=n(27723),l=n(56427),o=n(29491),i=n(47143),s=n(86087),c=n(92911),m=n(47767),d=n(75093),u=n(18062),p=n(27154),g=n(45048);const f=(0,i.withSelect)(e=>{const t=e("eventin/global");return{totalAttendees:t.getTotalAttendees(),isLoading:t.isResolving("getTotalAttendees")}}),v=(0,o.compose)(f)(function(e){const{totalAttendees:t,isLoading:n}=e,o=(0,m.Zp)();return(0,s.useLayoutEffect)(()=>{!n&&t>0&&o("/attendees",{replace:!0})},[t,n]),(0,a.createElement)(a.Fragment,null,(0,a.createElement)(l.Fill,{name:p.PQ},(0,a.createElement)(c.A,{justify:"space-between",align:"center"},(0,a.createElement)(u.A,{title:(0,r.__)("Attendees List","eventin")}))),(0,a.createElement)(g.A,null),(0,a.createElement)(d._W,null))});n.d(t,["default",0,v])},39251(e,t,n){var a=n(51609),r=n(69815);n(27723);const l=r.A.li`
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
`;n.d(t,["A",0,({text:e})=>(0,a.createElement)(l,null,e)])},1704(e,t,n){var a=n(69815),r=n(27154);const l=a.A.div`
	background-color: #ffffff;
	max-width: 1224px;
	margin: 40px auto;
	padding: 0 20px;

	.intro-title {
		text-wrap: balance;
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
		height: 48px;
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
		border-color: #f0eafc;

		&:hover {
			background-color: ${r.VG};
			color: #fff;
			border-color: transparent;
		}

		&:focus {
			outline: none;
		}
	}
`;n.d(t,["G",0,l,"V",0,o])},16162(e,t,n){var a=n(51609),r=n(86087),l=n(54725),o=n(7638),i=n(6836),s=n(1704);n.d(t,["A",0,()=>{const[e,t]=(0,r.useState)(!1),n=(0,i.QF)("/images/events/video-cover.webp");return(0,a.createElement)(s.V,null,e?(0,a.createElement)("iframe",{"aria-label":"demo-video",width:"100%",height:"372.5",src:"https://www.youtube.com/embed/vt3s7-vD8KQ?autoplay=1",allow:"accelerometer; autoplay",allowFullScreen:!0}):(0,a.createElement)(a.Fragment,null,(0,a.createElement)("img",{src:n,alt:"Eventin intro video"}),(0,a.createElement)(o.Ay,{variant:o.zB,icon:(0,a.createElement)(l.vUL,null),size:"large",className:"video-play-button",onClick:()=>t(!0)})))}])}}]);