"use strict";(globalThis.webpackChunkwp_event_solution=globalThis.webpackChunkwp_event_solution||[]).push([[8856],{11883(e,t,n){n.d(t,{A:()=>o});var i=n(51609),a=n(6836);const o=({height:e=22,width:t=22})=>(0,a.iconCreator)(()=>(({height:e,width:t})=>(0,i.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",fill:"none",viewBox:"0 0 20 20"},(0,i.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeLinejoin:"round",strokeWidth:"1.5",d:"M6.25 4.121h7.083c.69 0 1.25.56 1.25 1.25v1.25M12.5 10.788h-5M10 14.121H7.5"}),(0,i.createElement)("path",{stroke:"currentColor",strokeLinecap:"round",strokeWidth:"1.5",d:"M15.414 1.667H5.256c-.414 0-.837.06-1.172.306-1.062.78-1.88 2.517-.228 4.086.464.44 1.112.6 1.75.6h9.63c.662 0 1.847.095 1.847 2.114v6.211a3.34 3.34 0 0 1-3.33 3.35H6.226c-1.836 0-3.172-1.298-3.277-3.274L2.922 4.304"})))({height:e,width:t}))},70228(e,t,n){n.d(t,{A:()=>d});var i=n(51609),a=n(77278),o=n(16370),r=n(47152),l=n(75063);const d=()=>(0,i.createElement)(r.A,{gutter:[16,16]},[...Array(3)].map((e,t)=>(0,i.createElement)(o.A,{xs:24,sm:12,md:8,key:t},(0,i.createElement)(a.A,{style:{borderRadius:8}},(0,i.createElement)(l.A.Avatar,{active:!0,size:"large",shape:"circle",style:{marginBottom:16,marginRight:16}}),(0,i.createElement)(l.A.Input,{style:{width:200,marginBottom:8},active:!0}),(0,i.createElement)(l.A,{paragraph:{rows:4}}),(0,i.createElement)("div",{style:{display:"flex",gap:10,alignItems:"center",marginTop:16}},(0,i.createElement)(l.A.Button,{style:{width:100},active:!0}),(0,i.createElement)(l.A.Button,{style:{width:100},active:!0}))))))},19575(e,t,n){n.d(t,{A:()=>r});var i=n(52619),a=n(27723),o=n(64282);const r=async(e,t)=>{try{const n=await o.A.extensions.updateExtension({name:e,status:t});return(0,i.doAction)("eventin_notification",{type:"success",message:n?.message}),!0}catch(e){return(0,i.doAction)("eventin_notification",{type:"error",message:e?.message||(0,a.__)("Update failed! Please check the plugin list and try again.","eventin")}),!1}}},43129(e,t,n){n.d(t,{A:()=>d});var i=n(51609),a=n(56427),o=n(92911),r=n(18062),l=n(27154);function d(e){const{title:t}=e;return(0,i.createElement)(a.Fill,{name:l.PRIMARY_HEADER_NAME},(0,i.createElement)(o.A,{justify:"space-between",align:"center",wrap:"wrap",gap:20},(0,i.createElement)(r.A,{title:t})))}},10110(e,t,n){n.d(t,{A:()=>d});var i=n(51609),a=(n(27723),n(53441)),o=n(21409),r=n(42247),l=n(53303);const d=()=>(0,i.createElement)(i.Fragment,null,(0,i.createElement)(a.A,null),(0,i.createElement)(o.A,null),(0,i.createElement)(r.A,null),(0,i.createElement)(l.A,null))},53303(e,t,n){n.d(t,{A:()=>h});var i=n(51609),a=n(27723),o=n(29491),r=n(47143),l=n(86087),d=n(16370),s=n(47152),p=n(39041),c=n(56621),m=n(70228);const u=(0,r.withDispatch)(e=>{const t=e("eventin/global");return{invalidatePluginList:()=>t.invalidateResolution("getPluginList")}}),g=(0,r.withSelect)(e=>({pluginsList:e("eventin/global").getPluginList()})),h=(0,o.compose)(g,u)(e=>{const{pluginsList:t,invalidatePluginList:n}=e||{},[o,r]=(0,l.useState)(!0);return(0,l.useEffect)(()=>{null!=t&&r(!1)},[t]),o?(0,i.createElement)(m.A,null):(0,i.createElement)(p.YH,null,(0,i.createElement)("h3",null,(0,a.__)("Our plugins","eventin")),(0,i.createElement)("div",{className:"etn-module-section"},(0,i.createElement)(s.A,{gutter:[30,30]},t.map(e=>(0,i.createElement)(d.A,{key:e.name,xs:24,sm:12,xl:8},(0,i.createElement)(c.A,{...e,invalidatePluginList:n}))))))})},42247(e,t,n){n.d(t,{A:()=>m});var i=n(51609),a=n(27723),o=n(52741),r=n(92911),l=n(8228),d=n(39041);const s=()=>(0,i.createElement)("span",{className:"anticon"},(0,i.createElement)("svg",{width:14,height:14,viewBox:"0 0 14 14",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("path",{d:"M2.333 7.583L5.25 10.5l6.417-7",stroke:"#2F82FF",strokeWidth:"2",strokeLinecap:"round",strokeLinejoin:"round"}))),p=()=>(0,i.createElement)("span",{className:"anticon"},(0,i.createElement)("svg",{width:16,height:16,viewBox:"0 0 16 16",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("path",{d:"M9.333 1.333L2.667 9.333H8l-1.333 5.334 6.666-8H8l1.333-5.334z",stroke:"#fff",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round"}))),c=[(0,a.__)("Smart AI Booking — Let clients book 24/7 without back-and-forth emails","eventin"),(0,a.__)("Multi-Calendar Sync — Google, Outlook & iCal synced in real time","eventin"),(0,a.__)("Team Scheduling — Assign staff, manage availability across your whole team","eventin"),(0,a.__)("Automated Reminders — Reduce no-shows with SMS & email notifications","eventin"),(0,a.__)("Analytics Dashboard — Track revenue, peak hours & booking trends","eventin")],m=()=>{const e=(0,l.A)("/images/timetics-banner.webp");return(0,i.createElement)(i.Fragment,null,(0,i.createElement)(d.c5,null,(0,a.__)("Our SaaS","eventin")),(0,i.createElement)(d.HF,null,(0,i.createElement)(d.Qe,{align:"center",justify:"space-between",gap:64},(0,i.createElement)(d.Fh,{vertical:!0,gap:20},(0,i.createElement)(d.RJ,{align:"center",gap:8},(0,i.createElement)("span",{className:"etn-eyebrow-dot"}),(0,i.createElement)("span",{className:"etn-eyebrow-text"},(0,a.__)("AI-Powered Scheduling","eventin"))),(0,i.createElement)(r.A,{vertical:!0,gap:8},(0,i.createElement)(d.Ix,{align:"center",gap:12},(0,i.createElement)("h2",{className:"etn-saas-title"},(0,a.__)("Timetics AI","eventin")),(0,i.createElement)(d.JO,{align:"center"},(0,i.createElement)("span",{className:"etn-version-text"},(0,a.__)("v2.0","eventin")))),(0,i.createElement)(d.nZ,null)),(0,i.createElement)(d.DS,null,(0,a.__)("Timetics AI is your intelligent appointment scheduling assistant. Automate bookings, sync calendars, manage services, and delight your clients — all in one place.","eventin")),(0,i.createElement)(o.A,{style:{margin:0}}),(0,i.createElement)(r.A,{vertical:!0,gap:12},c.map((e,t)=>(0,i.createElement)(d.tR,{key:t,align:"center",gap:10},(0,i.createElement)(d.Ng,{align:"center",justify:"center"},(0,i.createElement)(s,null)),(0,i.createElement)("span",{className:"etn-feature-text"},e)))),(0,i.createElement)(d.uC,{align:"center",gap:12},(0,i.createElement)(d.uc,{type:"primary",size:"large",icon:(0,i.createElement)(p,null),href:"https://app.timetics.ai/sign-up",target:"_blank",rel:"noopener noreferrer"},(0,a.__)("Get Started Free","eventin")),(0,i.createElement)(d.i_,{size:"large",href:"https://timetics.ai/",target:"_blank",rel:"noopener noreferrer"},(0,a.__)("Learn more","eventin")))),(0,i.createElement)(d.su,null,(0,i.createElement)("img",{src:e,alt:(0,a.__)("Timetics AI Scheduling","eventin")})))))}},21409(e,t,n){n.d(t,{A:()=>p});var i=n(51609),a=n(27723),o=n(54725),r=n(8228),l=n(39041);const d=()=>(0,i.createElement)("svg",{width:"14",height:"14",viewBox:"0 0 14 14",fill:"none",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("rect",{x:"1",y:"5",width:"12",height:"8",rx:"1.5",fill:"white",opacity:"0.95"}),(0,i.createElement)("rect",{x:"0.5",y:"3.5",width:"13",height:"2",rx:"1",fill:"white"}),(0,i.createElement)("line",{x1:"7",y1:"3.5",x2:"7",y2:"13",stroke:"rgba(47,130,255,0.35)",strokeWidth:"1"}),(0,i.createElement)("path",{d:"M7 3.5C7 2 5.5 1 4.5 2S4 3.5 7 3.5",fill:"white",opacity:"0.9"}),(0,i.createElement)("path",{d:"M7 3.5C7 2 8.5 1 9.5 2S10 3.5 7 3.5",fill:"white",opacity:"0.9"})),s=()=>(0,i.createElement)("svg",{width:"16",height:"16",viewBox:"0 0 16 16",fill:"none",stroke:"#1a202c",strokeWidth:"1.5",strokeLinecap:"round",strokeLinejoin:"round",xmlns:"http://www.w3.org/2000/svg"},(0,i.createElement)("path",{d:"M2 6.5h12v8H2z"}),(0,i.createElement)("path",{d:"M1 4.5h14v2H1z"}),(0,i.createElement)("line",{x1:"8",y1:"4.5",x2:"8",y2:"14.5"}),(0,i.createElement)("path",{d:"M8 4.5C8 2.5 5.5 1.5 5 2.5S5.5 4.5 8 4.5"}),(0,i.createElement)("path",{d:"M8 4.5C8 2.5 10.5 1.5 11 2.5S10.5 4.5 8 4.5"})),p=()=>{const e=(0,r.A)("/images/referral-image.svg");return(0,i.createElement)(l.bZ,null,(0,i.createElement)("span",{className:"refer-top-left-shape"},(0,i.createElement)(o.AboutTopRightShapeIcon,null)),(0,i.createElement)("span",{className:"refer-bottom-right-shape"},(0,i.createElement)(o.AboutBottomShapeSvg,null)),(0,i.createElement)(l.c0,{align:"center",justify:"space-between"},(0,i.createElement)(l.ry,{vertical:!0,gap:20},(0,i.createElement)(l.vx,{align:"center",gap:6},(0,i.createElement)(d,null),(0,i.createElement)("span",{className:"etn-refer-badge-text"},(0,a.__)("30% Commission","eventin"))),(0,i.createElement)("div",null,(0,i.createElement)(l.gR,null,(0,a.__)("Refer & Earn","eventin")),(0,i.createElement)(l.Le,null,(0,a.__)("Share our plugin with your audience and earn a generous 30% commission for every successful sale.","eventin"))),(0,i.createElement)(l.Up,{type:"primary",size:"large",href:"https://themewinter.com/affiliate-program/",target:"_blank",rel:"noopener noreferrer",icon:(0,i.createElement)(s,null),iconPosition:"end"},(0,a.__)("Join Affiliate Program","eventin"))),(0,i.createElement)(l.Gi,null,(0,i.createElement)("img",{src:e,alt:(0,a.__)("Refer and earn illustration","eventin")}))))}},53441(e,t,n){n.d(t,{A:()=>d});var i=n(51609),a=n(27723),o=n(54725),r=n(39041),l=n(8228);const d=()=>{const e=(0,l.A)("/images/about_us_image.webp");return(0,i.createElement)(r.gV,null,(0,i.createElement)("span",{className:"about-top-right-shape"},(0,i.createElement)(o.AboutTopRightShapeIcon,null)),(0,i.createElement)("span",{className:"about-bottom-shape"},(0,i.createElement)(o.AboutBottomShapeSvg,null)),(0,i.createElement)("div",{className:"about-content-wrapper"},(0,i.createElement)("div",{className:"about-content"},(0,i.createElement)("h3",null,(0,a.__)("About Our Company","eventin")),(0,i.createElement)("p",null,(0,a.__)("Arraytics is a software company founded in 2013, specializing in WordPress, AI, Machine Learning, SaaS, and mobile applications. We’re committed to delivering high-quality tech solutions that help businesses grow and simplify people’s lives.","eventin")),(0,i.createElement)("p",null,(0,a.__)("Today, our products are trusted by nearly 70,000 customers across 120+ countries, powered by a passionate team of 30+ experts with over 12 years of industry experience. We’re proud to be a Level 12 author on Envato.","eventin"))),(0,i.createElement)("div",{className:"about-image"},(0,i.createElement)("img",{src:e,alt:(0,a.__)("About Image","eventin")}),(0,i.createElement)("span",{className:"about-image-u-shape"},(0,i.createElement)(o.AboutImageUShapeIcon,null)))))}},86382(e,t,n){n.d(t,{E:()=>o,r:()=>r});var i=n(6836),a=n(27723);const o=[{title:(0,a.__)("An Introduction to Eventin","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],description:(0,a.__)("New to Eventin? Start here. This quick guide breaks down how Eventin works and shows you how to build your first event in minutes.","eventin"),docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/eventin/introductions-of-eventin/",video_link:"https://www.youtube.com/watch?v=Vc2chtcGLDU"},{title:(0,a.__)("How to install & activate extensions","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],description:(0,a.__)("Get the most out of Eventin. Learn how to install premium or free extensions to expand functionality like Zoom, ticketing, and automation tools.","eventin"),docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/gettings-started/plugin-installation/",video_link:"https://www.youtube.com/watch?v=Qp1iRy1Ongw"},{title:(0,a.__)("Eventin Setup Walkthrough","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],description:(0,a.__)("A hands-on guide to walk you through the essential setup: event creation, calendar sync, ticket templates, and speaker layouts.","eventin"),docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/gettings-started/plugin-installation/",video_link:"https://www.youtube.com/watch?v=Qp1iRy1Ongw"},{title:(0,a.__)("Ticket Manage","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/event/create-event-tickets-free-paid/",video_link:"https://www.youtube.com/watch?v=Sq-fXHeakoM"},{title:(0,a.__)("Zoom Integration","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/integration/zoom-meeting-2/",video_link:"https://www.youtube.com/watch?v=RGApyZO5bGE&list=PLW54c-mt4ObDYahEXLuOjzYoygbe7Nz4C&index=9&pp=iAQB"},{title:(0,a.__)("Google Meet Integration","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/integration/google-meet/",video_link:"https://www.youtube.com/watch?v=7RCx6yIKULY"},{title:(0,a.__)("Attendee QR","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/attendee/qr-code/",video_link:"https://www.youtube.com/watch?v=tE6rjoZwnRY&list=PLW54c-mt4ObDYahEXLuOjzYoygbe7Nz4C&index=1&pp=iAQB0gcJCfYJAYcqIYzv"},{title:(0,a.__)("Event Template Setup","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/template-builder/how-to-create-event-templates/",video_link:"https://www.youtube.com/watch?v=0JUdhlaBOMs&pp=0gcJCfsJAYcqIYzv"},{title:(0,a.__)("Certificate Builder","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/template-builder/certificate-builder-for-attendee/",video_link:"https://www.youtube.com/watch?v=ETWc7ho7Kyc&list=PLW54c-mt4ObDYahEXLuOjzYoygbe7Nz4C&index=8&pp=iAQB"},{title:(0,a.__)("Organizer & Speaker Setup","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/speakers-and-organizers/how-to-create-eventin-speaker/",video_link:"https://www.youtube.com/watch?v=Naq6znx-oRg"},{title:(0,a.__)("Seatmap Integration","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/visual-seat-map/visual-seat-plan/",video_link:"https://www.youtube.com/watch?v=Vc2chtcGLDU&list=PLW54c-mt4ObDYahEXLuOjzYoygbe7Nz4C&index=14"},{title:(0,a.__)("Dokan Setup","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/integration/multivendor-event-marketplace/",video_link:"https://www.youtube.com/watch?v=OfOoL6b0nwc&t=9s"},{title:(0,a.__)("Attendee Extra Field","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/attendee/how-to-add-attendee-extra-fields/",video_link:"https://www.youtube.com/watch?v=Vc2chtcGLDU&list=PLW54c-mt4ObDYahEXLuOjzYoygbe7Nz4C&index=14"},{title:(0,a.__)("RSVP Setup","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/rsvp-settings/single-event-settings/",video_link:"https://www.youtube.com/watch?v=Qjoue63-O4A&list=PLW54c-mt4ObDYahEXLuOjzYoygbe7Nz4C&index=13&pp=iAQB"},{title:(0,a.__)("Event Email Automation","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/email-settings/automation/",video_link:"https://www.youtube.com/watch?v=e3OSwa1h0xU&list=PLW54c-mt4ObDYahEXLuOjzYoygbe7Nz4C&index=11&pp=iAQB"},{title:(0,a.__)("Hybrid Event Setup","eventin"),actions:[(0,a.__)("Read More","eventin"),(0,a.__)("Watch Tutorial","eventin")],docs_link:"https://support.themewinter.com/docs/plugins/plugin-docs/event/how-to-create-a-hybrid-event-in-eventin/",video_link:"https://www.youtube.com/watch?v=qysoO3ZVAYY&list=PLW54c-mt4ObDYahEXLuOjzYoygbe7Nz4C&index=5&pp=iAQB"}],r=[{image:(0,i.assetURL)("/images/pid1984.webp"),content:(0,a.__)("The free version outperforms many paid options, and has great performance. Works beautifully, looks great, and integrates with any theme I’ve tried. Great work, folks!.","eventin"),author:"Pid1984",rating:5},{image:(0,i.assetURL)("/images/rio_mastri.webp"),content:(0,a.__)("I have researched numerous event plugins to meet specific needs, and I have found that Eventin provides all the features necessary for creating a variety of event website types.","eventin"),author:"Rio Mastri",rating:5},{image:(0,i.assetURL)("/images/tony_brown.webp"),content:(0,a.__)("Arraytics has truly impressed me. their solutions work seamlessly, n their support team is absolutely topnotch. Any time I've had a question or a minor issue, theyve been incredibly responsive, knowledgeable, and genuinely eager to help. It's rare to find a company that combines excellent products with such outstanding customer service. Highly recommend.","eventin"),author:"Tony Brown ",rating:5},{image:(0,i.assetURL)("/images/Oyiadika_Millionaire.webp"),content:(0,a.__)("To be very honest, I have never seen a great support team than Arraytics support team. From the very day i started using the plugins, they have been very helpful on every issues i bring to them and fix it in less than no time to get my website fully active without any issues again... I would recommend to anyone who is interested in trying out their plugins.","eventin"),author:"Oyiadika Millionaire I.",rating:5}]},88976(e,t,n){n.d(t,{A:()=>c});var i=n(51609),a=n(27723),o=n(50400),r=n(16370),l=n(47152),d=n(54725),s=n(86382),p=n(39041);const c=()=>(0,i.createElement)(p.os,null,(0,i.createElement)("h1",null,(0,a.__)("Eventin Resources","eventin")),(0,i.createElement)(l.A,{gutter:[16,16]},s.E.map((e,t)=>(0,i.createElement)(r.A,{key:t,xs:24,sm:12,md:t<3?8:6,lg:t<3?8:6},(0,i.createElement)(p.ee,null,(0,i.createElement)("div",null,(0,i.createElement)(p.ZB,null,e.title),e.description&&(0,i.createElement)(p.BT,null,e.description)),(0,i.createElement)(p.PQ,{wrap:!0},e.actions.map((t,n)=>(0,i.createElement)(o.Ay,{type:"text",key:n,size:"small",icon:t.includes("Read")?(0,i.createElement)(d.AboutPageReadmoreIcon,null):(0,i.createElement)(d.AboutPagePlayIcon,null),onClick:()=>t.includes("Read")?window.open(e.docs_link,"_blank"):window.open(e.video_link,"_blank"),sx:{fontSize:"16px",lineHeight:500,color:"#595959"}},(0,i.createElement)("p",null,t)))))))))},29705(e,t,n){n.d(t,{A:()=>r});var i=n(51609),a=(n(27723),n(61660)),o=n(88976);const r=()=>(0,i.createElement)(i.Fragment,null,(0,i.createElement)(a.A,null),(0,i.createElement)(o.A,null))},61660(e,t,n){n.d(t,{A:()=>h});var i=n(51609),a=n(75093),o=n(7638),r=n(28266),l=n(55539),d=n(27723),s=n(36877),p=n(5394),c=n(92911),m=n(40372),u=n(86382),g=n(39041);const h=()=>{const e=localized_data_obj.admin_url+"admin.php?page=etn-wizard",{useBreakpoint:t}=m.Ay,n=t();return(0,i.createElement)(g.wn,null,(0,i.createElement)(g.MN,null,(0,i.createElement)("h2",{level:3},(0,d.__)("Setup Wizard","eventin")),(0,i.createElement)("p",{style:{display:"block",marginBottom:"20px"}},(0,d.__)("Launch Eventin in minutes with our guided Setup Wizard.","eventin")," ",n.md&&(0,i.createElement)("br",null),(0,d.__)("From event settings to ticketing and payments — we’ll walk you ","eventin"),n.md&&(0,i.createElement)("br",null),(0,d.__)(" through everything step by step.","eventin")),(0,i.createElement)(o.Ay,{variant:a.primary,href:e,sx:{height:"48px",paddingInline:"24px",fontSize:"16px"}},(0,d.__)("Get started","eventin"))),(0,i.createElement)(g.xe,null,(0,i.createElement)(p.A,{autoplay:!0,draggable:!0,dotPosition:"bottom",autoplaySpeed:4e3,dots:{className:"slick-dots-bottom"}},u.r.map((e,t)=>(0,i.createElement)(g._f,{key:t},(0,i.createElement)(g.UC,null,e.content.slice(0,130),"..."),(0,i.createElement)(g.wi,null,(0,i.createElement)(s.A,{src:e.image,size:"large",icon:(0,i.createElement)(l.A,null)}),(0,i.createElement)(g.A8,null,(0,i.createElement)(g.UM,null,e.author),(0,i.createElement)(c.A,{gap:2,align:"center"},Array.from({length:e.rating}).map((e,t)=>(0,i.createElement)(r.A,{key:t,style:{color:"#faad14"}}))))))))))}},78856(e,t,n){n.r(t),n.d(t,{default:()=>m});var i=n(51609),a=n(27723),o=n(86087),r=n(80560),l=n(75093),d=n(39041),s=n(29705),p=n(10110),c=n(43129);function m(){const[e,t]=(0,o.useState)("1"),n=[{key:"1",label:(0,a.__)("Getting Started","eventin"),children:(0,i.createElement)(s.A,null)},{key:"2",label:(0,a.__)("About us","eventin"),children:(0,i.createElement)(p.A,null)}];return(0,i.createElement)(d.ff,{className:"eventin-page-wrapper"},(0,i.createElement)(c.A,{title:(0,a.__)("1"===e?"Getting Started":"About us","eventin")}),(0,i.createElement)(d.MD,null,(0,i.createElement)(r.A,{defaultActiveKey:e,onTabClick:e=>t(e),items:n})),(0,i.createElement)(l.FloatingHelpButton,null))}},39041(e,t,n){n.d(t,{A8:()=>E,BT:()=>z,DS:()=>j,Fh:()=>I,Gi:()=>ee,HF:()=>O,Ix:()=>B,JO:()=>P,Le:()=>X,MD:()=>m,MN:()=>x,Ng:()=>D,PQ:()=>R,Qe:()=>C,RJ:()=>W,UC:()=>w,UM:()=>_,Up:()=>K,YH:()=>g,ZB:()=>A,_f:()=>v,bZ:()=>Q,c0:()=>Z,c5:()=>N,ee:()=>k,ff:()=>c,gR:()=>V,gV:()=>u,i_:()=>G,nZ:()=>Y,os:()=>y,ry:()=>J,su:()=>te,tR:()=>$,uC:()=>U,uc:()=>F,vx:()=>q,wi:()=>b,wn:()=>h,xe:()=>f});var i=n(6836),a=n(69815),o=n(50400),r=n(77278),l=n(92911);const d=(0,i.assetURL)("/images/setup_wizard.webp"),s=(0,i.assetURL)("/images/welcome_image.webp"),p=(0,i.assetURL)("/images/setup_widget_bg.webp"),c=a.default.div`
	background-color: #f4f6fa;
	padding: 12px 32px;
	min-height: 100vh;
`,m=(a.default.div`
	h3 {
		margin-top: 10px;
	}
	.etn-card-setup-wizard {
		position: relative;
		overflow: hidden;
		background-image: url( ${d} );
		background-position: 95% 100%;
		background-size: contain;
		background-repeat: no-repeat;

		@media screen and ( max-width: 1200px ) {
			background-position: 130% 0px;
			background-size: 72%;
		}
		@media screen and ( max-width: 992px ) {
			background-image: none;
		}
	}
	.etn-card-help-center {
		position: relative;
		overflow: hidden;
		background-image: url( ${s} );
		background-position: 100% 100%;
		background-size: contain;
		background-repeat: no-repeat;

		@media screen and ( max-width: 1200px ) {
			background-position: 130% 0px;
			background-size: 64%;
		}
		@media screen and ( max-width: 992px ) {
			background-image: none;
		}
	}
	.etn-card-help-cards {
		padding: 0px 40px;
		img {
			width: 80px;
			height: 80px;
		}
		@media screen and ( max-width: 992px ) {
			padding: 0px 10px;
			img {
				width: 50px;
				height: 50px;
			}
		}
	}
`,(0,a.default)(r.A)`
	border-radius: 8px;
	box-shadow: 0 4px 12px rgba( 0, 0, 0, 0.1 );
	text-align: center;
	margin-bottom: 0px;
	.ant-card-body {
		padding: 24px;
	}
	h3 {
		font-size: 18px;
		font-weight: bold;
	}
`,a.default.div`
	max-width: 1360px;
	margin: 0 auto;
	.ant-tabs-nav-wrap {
		background-color: white;
		border-radius: 0px;
	}

	.ant-tabs-tab {
		font-size: 16px;
		font-weight: 500;
		padding: 16px 30px;
		color: #334155;
	}
`),u=a.default.div`
	position: relative;
	padding: 20px 40px 60px 60px;
	background: #fff;
	overflow: hidden;
	border-radius: 8px;
	margin-bottom: 40px;

	@media screen and ( max-width: 768px ) {
		padding: 40px 20px 60px 20px;
		margin-bottom: 20px;
	}
	.about-content-wrapper {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 30px;

		@media screen and ( max-width: 768px ) {
			flex-direction: column;
			text-align: center;
		}
	}

	.about-content {
		flex-basis: 60%;
		@media screen and ( max-width: 768px ) {
			order: 2;
		}
	}
	.about-top-right-shape {
		position: absolute;
		top: 0;
		left: 0;
		z-index: 1;
		@media screen and ( max-width: 768px ) {
			display: none;
		}
	}

	.about-image-u-shape {
		position: absolute;
		top: 20px;
		right: 155px;
		z-index: 1;
		@media screen and ( max-width: 768px ) {
			display: none;
		}
	}

	.about-bottom-shape {
		position: absolute;
		bottom: -6px;
		right: 0px;
		@media screen and ( max-width: 768px ) {
			display: none;
		}
	}

	.about-image {
		border-radius: 8px;
		flex-basis: 40%;
		position: relative;

		@media screen and ( max-width: 768px ) {
			width: 100%;
			margin-top: 30px;
			order: 1;
		}
		img {
			width: 100%;
			height: auto;
			border-radius: 8px;
		}
	}

	h3 {
		font-size: 28px;
		font-weight: bold;
		margin-bottom: 20px;
		color: #111827;
		@media screen and ( max-width: 768px ) {
			font-size: 24px;
		}
	}

	p {
		font-size: 16px;
		color: #595959;
		line-height: 1.6;
		margin-bottom: 16px;
	}
`,g=a.default.div`
	padding: 20px 40px 60px 60px;
	background: #fff;
	overflow: hidden;
	border-radius: 8px;
	margin-bottom: 40px;

	@media screen and ( max-width: 768px ) {
		padding: 40px 20px 60px 20px;
		margin-bottom: 20px;
	}
	h3 {
		font-size: 28px;
		font-weight: bold;
		margin-bottom: 20px;
		color: #111827;
	}
`,h=a.default.div`
	display: flex;
	align-items: center;
	justify-content: space-between;
	background: #ffffff;
	border-radius: 12px;
	gap: 40px;
	min-height: 280px;
	padding-inline: 50px 80px;
	background-image: url( ${p} );
	background-repeat: no-repeat;
	background-position: right;

	@media screen and ( max-width: 992px ) {
		flex-direction: column;
		padding-inline: 30px;
		background-position: 95% 100%;
		align-items: flex-start;
		min-height: 300px;
		background-size: cover;
		padding-block: 20px 35px;
		gap: 20px;
	}

	@media screen and ( max-width: 768px ) {
		flex-direction: column;
		padding-inline: 20px;
		background-position: bottom right;
		align-items: flex-start;
		background-size: cover;
		padding-block: 20px 35px;
		gap: 20px;
	}
`,x=a.default.div`
	flex: 2;

	h2 {
		font-size: 26px;
		font-weight: 500;
		color: #212327;
		margin: 0px;
		margin-bottom: 12px;
	}

	p {
		color: #41454f;
		margin-bottom: 20px;
		font-size: 16px;
		line-height: 22px;
		margin: 0px;
		margin-bottom: 32px;
	}
`,f=a.default.div`
	flex: 1;
	max-width: 400px;
	width: 100%;

	.ant-carousel .slick-dots-bottom {
		bottom: -30px;

		li {
			height: 12px;
			width: 12px;
			border-radius: 100%;
			border: 1.5px solid #00000033;
			button {
				width: 12px;
				height: 12px;
				border-radius: 100%;
				background: #00000033;
			}
			&.slick-active {
				&::after {
					background: #00000033;
					opacity: 1;
					width: 100%;
					height: 100%;
					border-radius: 100%;
				}
			}
		}
	}

	@media screen and ( max-width: 992px ) {
		max-width: 100%;
	}
`,v=a.default.div`
	border-radius: 12px;
	padding: 20px;
	min-height: 160px;
	border: 1px solid #c3c5cb;
`,w=a.default.p`
	font-size: 16px;
	line-height: 22px;
	color: #41454f;
	margin-bottom: 20px;
`,b=a.default.div`
	display: flex;
	justify-content: flex-start;
	align-items: center;
	gap: 12px;

	img {
		width: 40px;
		height: 40px;
		border-radius: 50%;
		border: 1px solid #c3c5cb;
	}
`,_=a.default.span`
	font-weight: 500;
	color: #333;
`,E=a.default.div`
	display: flex;
	gap: 2px;
	flex-direction: column;
`,y=a.default.div`
	padding: 40px;
	background: #fff;
	margin-top: 40px;
	border-radius: 6px;

	@media screen and ( max-width: 768px ) {
		padding: 20px;
	}

	h1 {
		font-size: 26px;
		font-weight: 600;
		color: #212327;
		line-height: 38px;
		margin-bottom: 20px;
	}
`,k=a.default.div`
	border-radius: 12px;
	padding: 20px;
	border: 1px solid #d9d9d9;
	height: 100%;
	.ant-card-body {
		display: flex;
		flex-direction: column;
		justify-content: space-between;
		gap: 12px;
	}

	@media screen and ( max-width: 1024px ) {
		padding: 12px;
	}

	@media screen and ( max-width: 768px ) {
		padding: 10px;
	}
`,A=a.default.h3`
	font-size: 18px;
	font-weight: 600;
	margin: 0;
	margin-bottom: 12px;
	color: #262626;

	@media screen and ( max-width: 1024px ) {
		font-size: 16px;
	}

	@media screen and ( max-width: 768px ) {
		font-size: 14px;
	}
`,z=a.default.p`
	font-size: 14px;
	margin: 0;
	color: #414454;
	line-height: 20px;

	@media screen and ( max-width: 768px ) {
		font-size: 12px;
	}
`,R=(0,a.default)(l.A)`
	gap: 8px;
	margin-top: 10px;

	@media screen and ( max-width: 768px ) {
		gap: 0px;
		margin-top: 5px;
	}
`,L="#2f82ff",S="#1a6fe0",M="#ebf3ff",T="#0f172a",N=a.default.h3`
	font-size: 28px;
	font-weight: 700;
	color: #111827;
	margin: 40px 0 16px !important;
`,O=a.default.div`
	background: #ffffff;
	border-radius: 8px;
	padding: 40px 60px;
	margin-bottom: 40px;
	overflow: hidden;

	@media screen and ( max-width: 1024px ) {
		padding: 50px 40px;
	}

	@media screen and ( max-width: 768px ) {
		padding: 40px 20px;
		margin-bottom: 20px;
	}
`,C=(0,a.default)(l.A)`
	@media screen and ( max-width: 768px ) {
		flex-direction: column;
	}
`,I=(0,a.default)(l.A)`
	flex: 0 0 520px;
	max-width: 520px;

	@media screen and ( max-width: 1024px ) {
		flex: 1;
		max-width: 100%;
	}
`,W=(0,a.default)(l.A)`
	background: ${M};
	border-radius: 999px;
	padding: 6px 14px;
	width: fit-content;

	.etn-eyebrow-dot {
		width: 7px;
		height: 7px;
		border-radius: 50%;
		background: ${L};
		flex-shrink: 0;
	}

	.etn-eyebrow-text {
		font-size: 12px;
		font-weight: 600;
		color: ${L};
		line-height: 1.21;
	}
`,B=(0,a.default)(l.A)`
	.etn-saas-title {
		font-size: 32px;
		font-weight: 700;
		color: ${T};
		margin: 0;
		line-height: 1.21;
	}
`,P=(0,a.default)(l.A)`
	background: ${L};
	border-radius: 6px;
	padding: 4px 10px;
	flex-shrink: 0;

	.etn-version-text {
		font-size: 11px;
		font-weight: 700;
		color: #ffffff;
		line-height: 1.21;
	}
`,Y=a.default.div`
	width: 60px;
	height: 4px;
	background: ${L};
	border-radius: 2px;
`,j=a.default.p`
	font-size: 15px;
	font-weight: 400;
	color: #64748b;
	line-height: 1.7;
	margin: 0;
`,$=(0,a.default)(l.A)`
	.etn-feature-text {
		font-size: 14px;
		font-weight: 400;
		color: ${T};
		line-height: 1.21;
	}
`,D=(0,a.default)(l.A)`
	width: 28px;
	height: 28px;
	background: ${M};
	border-radius: 8px;
	flex-shrink: 0;

	svg {
		width: 14px;
		height: 14px;
	}
`,U=(0,a.default)(l.A)`
	@media screen and ( max-width: 480px ) {
		flex-direction: column;
		align-items: flex-start;
	}
`,H=(0,a.default)(o.Ay)`
	box-shadow: none;
	border-radius: 6px;
`,F=(0,a.default)(H)`
	&.ant-btn-variant-solid {
		background-color: ${L};
		border-color: ${L};
	}

	&.ant-btn-variant-solid:not(:disabled):not(.ant-btn-disabled) {
		&:hover,
		&:focus {
			background-color: ${S} !important;
			border-color: ${S} !important;
		}
	}
`,G=(0,a.default)(H)`
	color: ${L};
	border-color: ${L};

	&:not(:disabled):not(.ant-btn-disabled) {
		&:hover,
		&:focus {
			background-color: ${M} !important;
			color: ${S} !important;
			border-color: ${S} !important;
		}
	}
`,Q=a.default.div`
	position: relative;
	padding: 20px 40px 60px 60px;
	background: #fff;
	overflow: hidden;
	border-radius: 8px;
	margin-bottom: 40px;

	@media screen and ( max-width: 768px ) {
		padding: 40px 20px 60px 20px;
		margin-bottom: 20px;
	}

	.refer-top-left-shape {
		position: absolute;
		top: 0;
		left: 0;
		z-index: 1;
		@media screen and ( max-width: 768px ) {
			display: none;
		}
	}

	.refer-bottom-right-shape {
		position: absolute;
		bottom: -6px;
		right: 0px;
		@media screen and ( max-width: 768px ) {
			display: none;
		}
	}
`,Z=(0,a.default)(l.A)`
	gap: 40px;
	position: relative;
	z-index: 2;

	@media screen and ( max-width: 900px ) {
		flex-direction: column;
		gap: 24px;
	}
`,J=(0,a.default)(l.A)`
	flex-basis: 52%;

	@media screen and ( max-width: 900px ) {
		order: 2;
	}
`,q=(0,a.default)(l.A)`
	width: fit-content;
	background: #2f82ff;
	border-radius: 999px;
	padding: 6px 14px 6px 10px;

	.etn-refer-badge-text {
		font-size: 13px;
		font-weight: 600;
		color: #ffffff;
		line-height: 1.2;
	}
`,V=a.default.h3`
	font-size: 30px;
	font-weight: 800;
	color: #111827;
	margin: 0 0 12px !important;

	@media screen and ( max-width: 768px ) {
		font-size: 22px;
	}
`,X=a.default.p`
	font-size: 16px;
	color: #595959;
	line-height: 1.6;
	margin: 0;
	max-width: 380px;
`,K=(0,a.default)(o.Ay)`
	box-shadow: none;
	border-radius: 6px;
	width: fit-content;
	font-weight: 700;

	&.ant-btn-variant-solid {
		background-color: #f5b731;
		border-color: #f5b731;
		color: #1a202c;
	}

	&.ant-btn-variant-solid:not(:disabled):not(.ant-btn-disabled) {
		&:hover,
		&:focus {
			background-color: #e0a520 !important;
			border-color: #e0a520 !important;
			color: #1a202c !important;
		}
	}
`,ee=a.default.div`
	flex-basis: 46%;
	position: relative;

	@media screen and ( max-width: 900px ) {
		order: 1;
		width: 100%;
	}

	img {
		display: block;
		width: 100%;
		height: auto;
	}
`,te=a.default.div`
	flex: 0 0 580px;
	max-width: 580px;
	position: relative;
	border-radius: 24px;
	padding: 20px;

	&::before {
		content: "";
		position: absolute;
		inset: 0;
		border-radius: 24px;
		background: radial-gradient(
			ellipse at center,
			#ddeeff 0%,
			#eaf4ff 35%,
			#f0f7ff 60%,
			transparent 100%
		);
		z-index: 0;
	}

	@media screen and ( max-width: 1200px ) {
		flex: 0 0 420px;
		max-width: 420px;
	}

	@media screen and ( max-width: 1024px ) {
		flex: 1;
		max-width: 100%;
	}

	img {
		width: 100%;
		height: auto;
		border-radius: 16px;
		display: block;
		position: relative;
		z-index: 1;
	}
`},56621(e,t,n){n.d(t,{A:()=>x});var i=n(51609),a=n(86087),o=n(27723),r=n(11883),l=n(75093),d=n(7638),s=n(92911),p=n(67313),c=n(76450),m=n(19575);const{Title:u,Text:g,Link:h}=p.A,x=({name:e,title:t,description:n,status:p,notice:h,icon:x,settings_link:f,demo_link:v,doc_link:w,is_pro:b,upgrade_link:_,upgrade:E,deps:y,invalidatePluginList:k})=>{const[A,z]=(0,a.useState)(!1),R=async t=>{z(!0),await(0,m.A)(e,t),k(),z(!1)},L={fontSize:"16px",padding:"6px 14px"};return(0,i.createElement)(c.vi,null,(0,i.createElement)("div",{className:"etn-plugin-card-header"},x&&(0,i.createElement)("img",{src:x,alt:t,style:{width:"50px",height:"50px"}})),(0,i.createElement)("div",{className:"etn-plugin-card-body"},(0,i.createElement)(u,{level:4,style:{margin:"10px 0",fontSize:"20px"}},t),(0,i.createElement)("div",{className:"etn-card-desc"},(0,i.createElement)("div",{style:{marginBottom:"20px"}},(0,i.createElement)(g,{className:"etn-paragraph"},n.length>92?`${n.slice(0,92)}...`:n)," ",(0,i.createElement)("br",null),h&&(0,i.createElement)(g,{style:{display:"flex",color:"#ff7129",marginTop:"10px"}},h),(0,i.createElement)("div",{className:"etn-doc-link"},(0,i.createElement)(l.LinkText,{href:w,target:"_blank"},(0,i.createElement)(r.A,null)," ",(0,o.__)("Documentation","eventin")))))),(0,i.createElement)("div",{className:"etn-card-actions"},"on"==p&&(0,i.createElement)(d.Ay,{variant:d.zB,onClick:()=>{R("install")},target:"_blank",sx:L,loading:A},(0,o.__)("Install","eventin")),"install"==p&&(0,i.createElement)(d.Ay,{variant:d.zB,onClick:()=>{R("activate")},target:"_blank",sx:L,loading:A},(0,o.__)("Activate","eventin")),"upgrade"==p&&E&&(0,i.createElement)(d.Ay,{variant:d.zB,href:_,target:"_blank",sx:L,loading:A},(0,o.__)("Download","eventin")),"activate"==p&&(0,i.createElement)(s.A,{gap:20,wrap:"wrap"},(0,i.createElement)(d.Ay,{variant:d.Vt,onClick:()=>{R("deactivate")},target:"_blank",sx:L,loading:A},(0,o.__)("Deactivate","eventin")),f&&(0,i.createElement)(d.Ay,{variant:d.Vt,target:"_blank",sx:L,href:f},(0,o.__)("Configure","eventin")))))}},76450(e,t,n){n.d(t,{vi:()=>o});var i=n(27154),a=n(69815);a.default.div`
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
`,a.default.div`
	background: #fff;
	border-radius: 8px;
	margin-bottom: 30px;
	padding: 30px;
	@media ( max-width: 768px ) {
		padding: 20px;
	}
	.etn-extension-title {
		font-size: 24px;
		margin: 25px 0;
		padding: 14px 0;
		display: inline-block;
		border-bottom: 2px solid #d9d9d9;
		margin-top: 0;
	}
	.ant-tabs-tab {
		font-size: 16px;
		font-weight: 500;
		padding: 16px 30px;
	}
	.ant-tabs-top > .ant-tabs-nav::before {
		border-bottom: 2px solid #d9d9d9;
	}
`;const o=a.default.div`
	border-radius: 8px;
	margin: 0;
	min-height: 350px;
	overflow: hidden;
	position: relative;
	border: 1px solid #e6e6e6;
	border-radius: 8px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;

	.etn-plugin-card-header {
		display: flex;
		justify-content: space-between;
		gap: 20px;
		padding: 20px;
		@media ( max-width: 768px ) {
			flex-wrap: wrap;
		}
	}
	.etn-plugin-card-body {
		padding: 0 20px 0px 20px;
	}

	.etn-card-actions {
		padding: 20px;
		background: linear-gradient( 90deg, #eff6ff 0%, #f9f5ff 100% );
	}

	.etn-card-desc {
		font-size: 14px;
		color: #838790;

		.etn-paragraph {
			text-overflow: ellipsis;
			overflow: hidden;
		}
		.etn-doc-link {
			color: ${i.PRIMARY_COLOR};
			margin-top: 20px;
			a {
				display: flex;
				gap: 8px;
				font-size: 16px !important;
				font-weight: 600 !important;
				text-decoration: none !important;
			}
		}
		.etn-paragraph {
		}
	}
	.etn-link-button {
		color: ${i.PRIMARY_COLOR};
		font-size: 15px;
		font-weight: 600;
		margin-top: 10px;
		text-decoration: underline;
		&:hover {
			text-decoration: underline;
			color: ${i.PRIMARY_COLOR};
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
`;a.default.span`
	font-size: 24px;
	margin-right: 10px;
`,a.default.div`
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
`}}]);