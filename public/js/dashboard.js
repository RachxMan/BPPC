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

const orderNotOkeData = [
  { "ID NET":"TLK001","NAMA":"PT. Indosat Tbk","MASALAH":"Koneksi Terputus","AKSI":"Dalam Proses" },
  { "ID NET":"TLK002","NAMA":"PT. XL Axiata","MASALAH":"Bandwidth Lambat","AKSI":"Pending" },
  { "ID NET":"TLK003","NAMA":"PT. Smartfren","MASALAH":"Gangguan Jaringan","AKSI":"Escalated" }
];

const customerData = [
  { "ID NET":"CUS001","NAMA":"Budi Santoso","ALAMAT":"Jakarta Selatan","KONTAK":"081234567890","STATUS":"Active" },
  { "ID NET":"CUS002","NAMA":"Siti Aminah","ALAMAT":"Bandung","KONTAK":"081234567891","STATUS":"Active" },
  { "ID NET":"CUS003","NAMA":"Ahmad Rahman","ALAMAT":"Surabaya","KONTAK":"081234567892","STATUS":"Inactive" },
  { "ID NET":"CUS004","NAMA":"Dewi Sartika","ALAMAT":"Medan","KONTAK":"081234567893","STATUS":"Active" }
];

const sidebar = document.getElementById('sidebar');
const hamburger = document.getElementById('hamburger');
const overlay = document.getElementById('overlay');

function openSidebar(){
  sidebar.classList.add('open');
  overlay.classList.remove('hidden'); overlay.classList.add('show');
}
function closeSidebar(){
  sidebar.classList.remove('open');
  overlay.classList.add('hidden'); overlay.classList.remove('show');
}

hamburger.addEventListener('click', () => {
  if (sidebar.classList.contains('open')) closeSidebar(); else openSidebar();
});
overlay.addEventListener('click', closeSidebar);

function handleResize(){
  if (window.innerWidth > 900){
    sidebar.classList.remove('open');
    overlay.classList.add('hidden');
  }
}
window.addEventListener('resize', handleResize);

const kpiArea = document.getElementById('kpiArea');
function renderKPIs(){
  kpiArea.innerHTML = '';
  kpiData.forEach(k => {
    const el = document.createElement('div');
    el.className = 'kpi card';
    el.innerHTML = `
      <div class="kpi-title">${k.title}</div>
      <div class="kpi-value">
        <span>${k.value}</span>
        <small style="color:${k.trend.isPositive ? '#2ecc71':'#e74c3c'}">${k.trend.isPositive?'+':''}${k.trend.value}</small>
      </div>
    `;
    kpiArea.appendChild(el);
  });
}
renderKPIs();

function populateTable(tableId, data, columns){
  const tbody = document.querySelector(`#${tableId} tbody`);
  tbody.innerHTML = '';
  if (!data || data.length === 0){
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

function setCanvasSize(canvas, height=260){
  const rect = canvas.getBoundingClientRect();
  const dpr = window.devicePixelRatio || 1;
  canvas.width = Math.max(300, Math.floor(rect.width * dpr));
  canvas.height = Math.floor(height * dpr);
  canvas.style.height = height + 'px';
  const ctx = canvas.getContext('2d');
  ctx.setTransform(dpr,0,0,dpr,0,0);
  return ctx;
}

const pieCanvas = document.getElementById('pieChart');
let pieSlices = [];
const pieTooltip = createTooltip();

function drawPie(){
  pieSlices = [];
  const ctx = setCanvasSize(pieCanvas, 260);
  ctx.clearRect(0,0,pieCanvas.width, pieCanvas.height);
  const cssW = pieCanvas.clientWidth;
  const cssH = parseInt(pieCanvas.style.height || 260);
  const radius = Math.min(cssW, cssH) * 0.25;
  const legendWidth = 140;
  const chartWidth = radius*2 + 24 + legendWidth;
  const startX = (cssW - chartWidth)/2;
  const centerX = startX + radius;
  const centerY = cssH / 2;

  const total = paymentStatusData.reduce((s,d)=>s+d.value,0);
  let angle = -Math.PI/2;
  paymentStatusData.forEach(item => {
    const sliceAngle = (item.value/total) * Math.PI * 2;
    ctx.beginPath();
    ctx.moveTo(centerX, centerY);
    ctx.fillStyle = item.color;
    ctx.arc(centerX, centerY, radius, angle, angle + sliceAngle);
    ctx.closePath();
    ctx.fill();

    pieSlices.push({
      startAngle: angle,
      endAngle: angle + sliceAngle,
      centerX, centerY, radius,
      label: item.label, value: item.value, color: item.color
    });
    angle += sliceAngle;
  });

  const legendX = centerX + radius + 24;
  const legendY = centerY - (paymentStatusData.length * 18);
  ctx.font = '13px Arial';
  ctx.textBaseline = 'middle';
  paymentStatusData.forEach((item,i)=>{
    ctx.fillStyle = item.color;
    ctx.fillRect(legendX, legendY + i*28, 16, 16);
    ctx.fillStyle = '#000';
    ctx.fillText(`${item.label} (${Math.round(item.value/total*100)}%)`, legendX + 22, legendY + i*28 + 8);
  });
}
drawPie();

pieCanvas.addEventListener('mousemove', (e) => {
  const rect = pieCanvas.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const y = e.clientY - rect.top;
  let found = false;
  pieSlices.forEach(s => {
    const dx = x - s.centerX;
    const dy = y - s.centerY;
    const dist = Math.sqrt(dx*dx + dy*dy);
    if (dist <= s.radius){
      let ang = Math.atan2(dy, dx);
      if (ang < 0) ang += Math.PI*2;
      let start = s.startAngle; let end = s.endAngle;
      if (start < 0){ start += Math.PI*2; end += Math.PI*2; ang = (ang < start ? ang + Math.PI*2 : ang); }
      if (ang >= start && ang <= end){
        showTooltip(pieTooltip, e.pageX, e.pageY, `<b>${s.label}</b><br>${s.value}`);
        found = true;
      }
    }
  });
  if (!found) pieTooltip.style.display = 'none';
});
pieCanvas.addEventListener('mouseleave', ()=> pieTooltip.style.display = 'none');

const barCanvas = document.getElementById('barChart');
let bars = [];
const barTooltip = createTooltip();

function drawBars(){
  bars = [];
  const ctx = setCanvasSize(barCanvas, 260);
  ctx.clearRect(0,0,barCanvas.width, barCanvas.height);
  const cssW = barCanvas.clientWidth;
  const cssH = parseInt(barCanvas.style.height || 260);
  const baseline = cssH - 40;

  const barWidth = Math.min(60, (cssW - 80) / (progressData.length*1.8));
  const spacing = 24;
  const chartWidth = progressData.length * barWidth + (progressData.length - 1) * spacing;
  const startX = (cssW - chartWidth)/2;

  ctx.beginPath(); ctx.moveTo(20, baseline); ctx.lineTo(cssW - 20, baseline); ctx.strokeStyle = '#bbb'; ctx.stroke();

  const maxVal = Math.max(...progressData.map(d=>d.value), 100);
  progressData.forEach((d,i) => {
    const x = startX + i * (barWidth + spacing);
    const height = Math.max(6, (d.value / maxVal) * (cssH - 80));
    const y = baseline - height;
    ctx.fillStyle = d.color;
    ctx.fillRect(x, y, barWidth, height);

    ctx.fillStyle = '#000'; ctx.font = '12px Arial'; ctx.textAlign = 'center';
    ctx.fillText(`${d.value}%`, x + barWidth/2, y - 8);
    ctx.fillText(d.label, x + barWidth/2, baseline + 18);

    bars.push({ x, y, width: barWidth, height, label: d.label, value: d.value });
  });
}
drawBars();

barCanvas.addEventListener('mousemove', (e) => {
  const rect = barCanvas.getBoundingClientRect();
  const mouseX = e.clientX - rect.left, mouseY = e.clientY - rect.top;
  let found = false;
  bars.forEach(b => {
    if (mouseX >= b.x && mouseX <= b.x + b.width && mouseY >= b.y && mouseY <= b.y + b.height){
      showTooltip(barTooltip, e.pageX, e.pageY, `<b>${b.label}</b><br>${b.value}%`);
      found = true;
    }
  });
  if (!found) barTooltip.style.display = 'none';
});
barCanvas.addEventListener('mouseleave', ()=> barTooltip.style.display = 'none');

function createTooltip(){
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
function showTooltip(el, pageX, pageY, html){
  el.innerHTML = html;
  el.style.left = (pageX + 12) + 'px';
  el.style.top = (pageY - 28) + 'px';
  el.style.display = 'block';
}

function redrawAll(){
  drawPie();
  drawBars();
}
window.addEventListener('resize', () => {
  clearTimeout(window._resizeTO);
  window._resizeTO = setTimeout(redrawAll, 120);
});

document.getElementById('menu').addEventListener('click', (e) => {
  const li = e.target.closest('li');
  if (!li) return;
  document.querySelectorAll('.menu li').forEach(x => x.classList.remove('active'));
  li.classList.add('active');
});

window.addEventListener('load', () => {
  setTimeout(() => { redrawAll(); handleResize(); }, 50);
});
