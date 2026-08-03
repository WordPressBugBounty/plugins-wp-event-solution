"use strict";(globalThis.webpackChunkwp_event_solution||=[]).push([[5761],{40728(e,t,n){var a=n(51609),o=n(27723),i=n(50400),r=n(89500),l=n(36492),c=n(99150),p=n(72121),d=n(99489);n.d(t,["A",0,({total:e=0,currentPage:t=1,pageSize:n=10,onPageChange:s,onPageSizeChange:g,pageSizeOptions:f=["5","10","20","50","100"],wrapperClassName:m="eventin-pagination-wrapper"})=>{const u=0===e?0:(t-1)*n+1,h=Math.min(t*n,e),x=e=>{s&&s(e)};return(0,a.createElement)(d.C,{className:m},(0,a.createElement)("div",{className:"pagination-left"},(0,a.createElement)("span",{className:"rows-per-page-label"},(0,o.__)("Rows per page:","eventin")),(0,a.createElement)(l.A,{value:n.toString(),onChange:e=>{g&&g(e)},options:f.map(e=>({value:e,label:e})),size:"middle"})),(0,a.createElement)("div",{className:"pagination-right"},(0,a.createElement)("span",{className:"pagination-info"},u,"-",h," ",(0,o.__)("of","eventin")," ",e),(0,a.createElement)(r.A,{current:t,total:e,pageSize:n,onChange:x,showSizeChanger:!1,showQuickJumper:!1,showTotal:!1,prevIcon:(0,a.createElement)(i.A,{icon:(0,a.createElement)(c.A,null),iconPosition:"start",variant:"outlined",onClick:()=>x(t-1),disabled:1===t,style:{height:"100%"}},(0,o.__)("Previous","eventin")),nextIcon:(0,a.createElement)(i.A,{icon:(0,a.createElement)(p.A,null),iconPosition:"end",variant:"outlined",onClick:()=>x(t+1),disabled:t===e,style:{height:"100%"}},(0,o.__)("Next","eventin")),simple:!1})))}])},99489(e,t,n){const a=n(69815).A.div`
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
`;n.d(t,["C",0,a])},16017(e,t,n){var a=n(69815),o=n(36492);const i=a.A.div`
	background-color: #f4f6fa;
	padding: 12px 32px;
	min-height: 100vh;

	.ant-table-wrapper {
		padding: 15px;
		background-color: #fff;
		border-radius: 12px;
	}

	.event-list-wrapper {
		border-radius: 12px;
	}

	.ant-table-thead {
		> tr {
			> th {
				background-color: #ffffff;
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

	.coupon-code {
		color: #262626;
		font-size: 16px;
		font-weight: 600;
	}

	.event-actions {
		.anticon {
			font-size: 16px;
			color: #525266;
		}
	}
`,r=(0,a.A)(o.A)`
	min-width: 180px;

	.ant-select-selector {
		height: 36px !important;
		border-radius: 8px;
		border: 1px solid #e5e7eb;
		background-color: #fff;
		color: #334155;
		font-size: 14px;
	}
`;n.d(t,["C",0,r,"f",0,i])},85761(e,t,n){n.r(t),n.d(t,{default:()=>b});var a=n(51609),o=n(27723),i=n(56427),r=n(86087),l=n(92911),c=n(47767),p=n(26557),d=n(7638),s=n(18062),g=n(75093),f=n(40728),m=n(27154),u=n(64282),h=n(16017);const x=[{title:(0,o.__)("Buyer","eventin"),dataIndex:"buyer_email",key:"buyer_email"},{title:(0,o.__)("Order","eventin"),dataIndex:"order_id",key:"order_id",render:e=>`#${e}`},{title:(0,o.__)("Date","eventin"),dataIndex:"date",key:"date"},{title:(0,o.__)("Discount","eventin"),dataIndex:"discount_amount",key:"discount_amount",render:e=>`${e}`}];function b(){const{id:e}=(0,c.g)(),t=(0,c.Zp)(),[n,b]=(0,r.useState)({items:[],total_items:0}),[v,w]=(0,r.useState)(!0),[_,k]=(0,r.useState)(""),[E,y]=(0,r.useState)(m.X$.paged),[C,A]=(0,r.useState)(m.X$.per_page);return(0,r.useEffect)(()=>{u.A.coupons.singleCoupon(e).then(e=>k(e?.code||"")).catch(()=>k(""))},[e]),(0,r.useEffect)(()=>{w(!0),u.A.coupons.redemptions(e,{paged:E,per_page:C}).then(b).finally(()=>w(!1))},[e,E,C]),(0,a.createElement)(h.f,{className:"eventin-page-wrapper"},(0,a.createElement)(i.Fill,{name:m.PQ},(0,a.createElement)(l.A,{align:"center",gap:16},(0,a.createElement)(d.Ay,{variant:d.Vt,icon:(0,a.createElement)(p.A,null),sx:{height:"36px",width:"36px",backgroundColor:"#fafafa",borderColor:"transparent",lineHeight:"1"},onClick:()=>t("/coupons")}),(0,a.createElement)(s.A,{title:`${(0,o.__)("Coupon Usage","eventin")}: ${_}`}))),(0,a.createElement)("div",{className:"event-list-wrapper"},(0,a.createElement)(g.Ee,{columns:x,dataSource:n.items,loading:v,rowKey:e=>`${e.order_id}`,showPagination:!1}),(0,a.createElement)(f.A,{total:n.total_items,currentPage:E,pageSize:C,onPageChange:e=>y(Number(e)),onPageSizeChange:e=>{A(Number(e)),y(1)}})))}}}]);