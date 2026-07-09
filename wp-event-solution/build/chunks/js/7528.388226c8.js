"use strict";(globalThis.webpackChunkwp_event_solution=globalThis.webpackChunkwp_event_solution||[]).push([[7528],{77579(e,t,a){var n=a(51609),r=a(16370),l=a(92911),o=a(47152),i=a(84976),s=a(27723),c=a(7638),m=a(57237),d=a(74672),u=a(21809),p=a(65887);a.d(t,["A",0,()=>(0,n.createElement)(u.G,{className:"wrapper"},(0,n.createElement)(o.A,{className:"intro",gutter:60,align:"middle"},(0,n.createElement)(r.A,{xs:24,sm:24,md:24,lg:12},(0,n.createElement)(p.A,null)),(0,n.createElement)(r.A,{xs:24,sm:24,md:24,lg:12},(0,n.createElement)(l.A,{vertical:!0},(0,n.createElement)(m.A,{className:"intro-title",level:2,sx:{color:"#0C274A"}},(0,s.__)("Bring your sessions to life with interactive schedules","eventin")),(0,n.createElement)("ul",{className:"intro-list"},(0,n.createElement)(d.A,{text:(0,s.__)("Keep your meetings on track and boost your productivity","eventin")}),(0,n.createElement)(d.A,{text:(0,s.__)("Save schedules as templates & use them time & again","eventin")}),(0,n.createElement)(d.A,{text:(0,s.__)("Create and manage your personal schedules from here","eventin")})),(0,n.createElement)(l.A,{className:"intro-actions",justify:"start",align:"center",gap:12},(0,n.createElement)(i.N_,{to:"/schedules/create"},(0,n.createElement)(c.Ay,{variant:c.zB,className:"intro-button"},(0,s.__)("Let's Start Creating","eventin"))))))))])},77528(e,t,a){a.r(t);var n=a(51609),r=a(92911),l=a(47767),o=a(56427),i=a(29491),s=a(47143),c=a(86087),m=a(27723),d=a(75093),u=a(18062),p=a(27154),g=a(77579);const h=(0,s.withSelect)(e=>{const t=e("eventin/global");return{totalSchedules:t.getTotalSchedules(),isLoading:t.isResolving("getTotalSchedules")}}),f=(0,i.compose)(h)(function(e){const{totalSchedules:t,isLoading:a}=e,i=(0,l.Zp)();return(0,c.useLayoutEffect)(()=>{!a&&t>0&&i("/schedules",{replace:!0})},[t,a]),(0,n.createElement)(n.Fragment,null,(0,n.createElement)(o.Fill,{name:p.PQ},(0,n.createElement)(r.A,{justify:"space-between",align:"center"},(0,n.createElement)(u.A,{title:(0,m.__)("Schedule List","eventin")}))),(0,n.createElement)(g.A,null),(0,n.createElement)(d._W,null))});a.d(t,["default",0,f])},74672(e,t,a){var n=a(51609),r=a(69815);a(27723);const l=r.A.li`
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
`;a.d(t,["A",0,({text:e})=>(0,n.createElement)(l,null,e)])},21809(e,t,a){var n=a(69815),r=a(27154);const l=n.A.div`
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
`,o=n.A.div`
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
`;a.d(t,["G",0,l,"V",0,o])},65887(e,t,a){var n=a(51609),r=a(86087),l=a(54725),o=a(7638),i=a(6836),s=a(21809);a.d(t,["A",0,()=>{const[e,t]=(0,r.useState)(!1),a=(0,i.QF)("/images/events/video-cover.webp");return(0,n.createElement)(s.V,null,e?(0,n.createElement)("iframe",{"aria-label":"demo-video",width:"100%",height:"372.5",src:"https://www.youtube.com/embed/4iAYtGGP1t0?si=3Trk9p45_0wxzg8W?autoplay=1",allow:"accelerometer; autoplay",allowFullScreen:!0}):(0,n.createElement)(n.Fragment,null,(0,n.createElement)("img",{src:a,alt:"Eventin intro video"}),(0,n.createElement)(o.Ay,{variant:o.zB,icon:(0,n.createElement)(l.vUL,null),size:"large",className:"video-play-button",onClick:()=>t(!0)})))}])}}]);