import { f7 as c, k as g, f8 as n } from "./commonChunk.xfeNmr3p.js";
const o = "_statusDisplay_gg8rt_30",
  r = "_statusIcon_gg8rt_37",
  _ = "_statusText_gg8rt_47",
  i = "_pending_gg8rt_53",
  u = "_success_gg8rt_56",
  a = "_failed_gg8rt_59",
  l = "_pendingBg_gg8rt_62",
  p = "_successBg_gg8rt_65",
  d = "_failedBg_gg8rt_68",
  E = "_amount_gg8rt_71",
  f = "_copy_gg8rt_78",
  C = "_remark_gg8rt_87",
  m = "_currencyAmount_gg8rt_93",
  y = "_noWalletTips_gg8rt_96",
  B = "_btn_gg8rt_100",
  t = {
    statusDisplay: o,
    statusIcon: r,
    statusText: _,
    pending: i,
    success: u,
    failed: a,
    pendingBg: l,
    successBg: p,
    failedBg: d,
    amount: E,
    copy: f,
    remark: C,
    currencyAmount: m,
    noWalletTips: y,
    btn: B,
  },
  A = (s) => ![n.SUCCESS, n.ENFORCE, n.CANCEL, n.FAILED, n.REJECT].includes(s),
  T = (s) =>
    s === n.SUCCESS || s === n.ENFORCE
      ? "comm_icon_pay_1"
      : s === n.CANCEL || s === n.FAILED || s === n.REJECT
      ? "comm_icon_pay_2"
      : "comm_icon_pay_3",
  R = (s) =>
    s
      ? {
          [n.SUCCESS]: [t.success, t.successBg],
          [n.ENFORCE]: [t.success, t.successBg],
          [n.CANCEL]: [t.failed, t.failedBg],
          [n.FAILED]: [t.failed, t.failedBg],
          [n.REJECT]: [t.failed, t.failedBg],
        }[s] || [t.pending, t.pendingBg]
      : [t.pending, t.pendingBg],
  h = (s) =>
    ""
      .concat(s == null ? void 0 : s.currencySign, "1:")
      .concat(c())
      .concat(
        g(s == null ? void 0 : s.exchangeRate, { showGroup: !0 })
          .toFixed({ decimalPlaces: 10, tailZero: !1 })
          .toString()
      );
export { T as a, A as b, h as e, R as g, t as s };
//# sourceMappingURL=WithdrawRecordIndex.BFN8CYlK.js.map
