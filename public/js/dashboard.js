document.addEventListener('DOMContentLoaded', function () {

  // === DATA ===
  const kpiData = [
    { title: "Total Pelanggan", value: "1,248", trend: { value: "12%", isPositive: true } },
    { title: "Total Follow-up", value: "994", trend: { value: "8%", isPositive: true } },
    { title: "Recent Follow-up", value: "264", trend: { value: "15%", isPositive: true } },
    { title: "Progress Collection", value: "78.8%", trend: { value: "5%", isPositive: true } }
  ];

  const paymentStatusData = [
    { label: "Paid", value: 652, color: "#2ecc71" },
    { label: "Unpaid", value: 342, color: "#e74c3c" }
  ];

  const progressData = [
    { label: "Week 1", value: 85, color: "#e67e22" },
    { label: "Week 2", value: 72, color: "#3498db" },
    { label: "Week 3", value: 94, color: "#f1c40f" },
    { label: "Week 4", value: 67, color: "#2ecc71" }
  ];

  const orderNotOkeData = [];
  const customerData = [];

  // === RENDER KPI ===
  const kpiArea = document.getElementById('kpiArea');
  function renderKPIs() {
    if (!kpiArea) return;
    kpiArea.innerHTML = '';
    kpiData.forEach(k => {
      const el = document.createElement('div');
      el.className = 'kpi card';
      el.innerHTML = `
        <div class="kpi-title">${k.title}</div>
        <div class="kpi-value">
          <span>${k.value}</span>
          <small style="color:${k.trend.isPositive ? '#2ecc71' : '#e74c3c'}">
            ${k.trend.isPositive ? '+' : ''}${k.trend.value}
          </small>
        </div>`;
      kpiArea.appendChild(el);
    });
  }
  renderKPIs();


  // === TABLE UTILITY ===
  function populateTable(tableId, data, columns) {
    const tbody = document.querySelector(`#${tableId} tbody`);
    if (!tbody) return;
    tbody.innerHTML = '';
    if (!data || data.length === 0) {
      const tr = document.createElement('tr');
      const td = document.createElement('td');
      td.colSpan = columns;
      td.className = 'empty-row';
      td.textContent = '-';
      tr.appendChild(td);
      tbody.appendChild(tr);
      return;
    }
    data.forEach(row => {
      const tr = document.createElement('tr');
      Object.keys(row).forEach(k => {
        const td = document.createElement('td');
        td.textContent = row[k];
        tr.appendChild(td);
      });
      tbody.appendChild(tr);
    });
  }
  populateTable('orderTable', orderNotOkeData, 4);
  populateTable('customerTable', customerData, 5);


  // === CHART UTILITY ===
  function setCanvasSize(canvas, height = 260) {
    if (!canvas) return null;
    const rect = canvas.getBoundingClientRect();
    const dpr = window.devicePixelRatio || 1;
    canvas.width = Math.max(300, Math.floor(rect.width * dpr));
    canvas.height = Math.floor(height * dpr);
    canvas.style.height = height + 'px';
    const ctx = canvas.getContext('2d');
    ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
    return ctx;
  }

  const pieCanvas = document.getElementById('pieChart');
  const barCanvas = document.getElementById('barChart');
  let pieSlices = [], bars = [];
  const pieTooltip = createTooltip();
  const barTooltip = createTooltip();


  function drawPie() {
    if (!pieCanvas) return;
    const ctx = setCanvasSize(pieCanvas, 260);
    if (!ctx) return;
    const cssW = pieCanvas.clientWidth;
    const cssH = parseInt(pieCanvas.style.height || 260);
    const radius = Math.min(cssW, cssH) * 0.25;
    const legendWidth = 140;
    const chartWidth = radius * 2 + 24 + legendWidth;
    const startX = (cssW - chartWidth) / 2;
    const centerX = startX + radius;
    const centerY = cssH / 2;

    const total = paymentStatusData.reduce((s, d) => s + d.value, 0);
    let angle = -Math.PI / 2;
    paymentStatusData.forEach(item => {
      const sliceAngle = (item.value / total) * Math.PI * 2;
      ctx.beginPath();
      ctx.moveTo(centerX, centerY);
      ctx.fillStyle = item.color;
      ctx.arc(centerX, centerY, radius, angle, angle + sliceAngle);
      ctx.closePath();
      ctx.fill();
      angle += sliceAngle;
    });
  }
  drawPie();


  function drawBars() {
    if (!barCanvas) return;
    const ctx = setCanvasSize(barCanvas, 260);
    if (!ctx) return;
    const cssW = barCanvas.clientWidth;
    const cssH = parseInt(barCanvas.style.height || 260);
    const baseline = cssH - 40;
    const barWidth = Math.min(60, (cssW - 80) / (progressData.length * 1.8));
    const spacing = 24;
    const startX = (cssW - (progressData.length * (barWidth + spacing))) / 2;

    ctx.beginPath();
    ctx.moveTo(20, baseline);
    ctx.lineTo(cssW - 20, baseline);
    ctx.strokeStyle = '#bbb';
    ctx.stroke();

    const maxVal = Math.max(...progressData.map(d => d.value), 100);
    progressData.forEach((d, i) => {
      const x = startX + i * (barWidth + spacing);
      const height = Math.max(6, (d.value / maxVal) * (cssH - 80));
      const y = baseline - height;
      ctx.fillStyle = d.color;
      ctx.fillRect(x, y, barWidth, height);
    });
  }
  drawBars();

  // === TOOLTIPS ===
  function createTooltip() {
    const el = document.createElement('div');
    el.style.position = 'absolute';
    el.style.padding = '6px 8px';
    el.style.background = 'rgba(0,0,0,0.8)';
    el.style.color = '#fff';
    el.style.fontSize = '13px';
    el.style.borderRadius = '6px';
    el.style.pointerEvents = 'none';
    el.style.zIndex = 1200;
    el.style.display = 'none';
    document.body.appendChild(el);
    return el;
  }

  // === REDRAW ON RESIZE ===
  function redrawAll() {
    drawPie();
    drawBars();
  }
  window.addEventListener('resize', () => {
    clearTimeout(window._resizeTO);
    window._resizeTO = setTimeout(redrawAll, 120);
  });

}); // ← tutup DOMContentLoaded
