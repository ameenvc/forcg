/* Local-only PowerPoint generation. Native charts remain editable in PowerPoint. */
(function () {
    'use strict';
    let library;
    function load(url) {
        if (window.PptxGenJS) return Promise.resolve();
        if (!library) library = new Promise((resolve, reject) => {
            const script = document.createElement('script'); script.src = url;
            script.onload = () => window.PptxGenJS ? resolve() : reject(new Error('PowerPoint library unavailable'));
            script.onerror = () => { script.remove(); reject(new Error('PowerPoint library failed to load')); };
            document.head.appendChild(script);
        }).catch(error => { library = null; throw error; });
        return library;
    }
    const tones = ['B73C58', 'AD761B', '208365', '7B879C'];
    const levels = ['High', 'Moderate', 'Low', 'Unassessed'];
    const short = (value, limit = 75) => String(value).length > limit ? String(value).slice(0, limit - 1) + '…' : String(value);
    async function powerPoint(view) {
        await load(view.libraryUrl);
        const pptx = new window.PptxGenJS(); pptx.layout = 'LAYOUT_WIDE';
        pptx.author = 'CGBOD'; pptx.company = 'CGBOD'; pptx.subject = 'Enterprise risk analysis';
        pptx.title = 'Risk analysis — ' + view.scope; pptx.lang = 'en-GB';
        pptx.theme = {headFontFace: 'Arial', bodyFontFace: 'Arial', lang: 'en-GB'};
        let slideNumber = 0;
        function slide(title, subtitle) {
            const page = pptx.addSlide(); page.background = {color: 'F6F8FC'};
            page.addShape(pptx.ShapeType.rect, {x: 0, y: 0, w: 7, h: .05, line: {transparency: 100}, fill: {color: '365FD1'}});
            page.addShape(pptx.ShapeType.rect, {x: 7, y: 0, w: 6.333, h: .05, line: {transparency: 100}, fill: {color: '7960C4'}});
            page.addText(short(title, 85), {x: .55, y: .35, w: 12.2, h: .5, fit: 'shrink', fontSize: 25, bold: true, color: '263B60', margin: 0, breakLine: false});
            page.addText(short(subtitle || view.scope, 150), {x: .55, y: .98, w: 12.2, h: .35, fit: 'shrink', fontSize: 12, color: '667A96', margin: 0});
            page.addText('CGBOD  ·  Enterprise risk management  ·  Saved assessment data', {x: .55, y: 7.08, w: 10.5, h: .2, fontSize: 9, color: '74839B', margin: 0});
            page.addText(String(++slideNumber), {x: 12, y: 7.08, w: .7, h: .2, fontSize: 9, align: 'right', color: '74839B', margin: 0});
            return page;
        }
        function chartOptions(extra) {
            return Object.assign({x: .55, y: 1.6, w: 12.2, h: 5.05,
                chartColors: tones, showLegend: true, legendPos: 'b', legendFontFace: 'Arial', legendFontSize: 11,
                catAxisLabelFontFace: 'Arial', catAxisLabelFontSize: 12, catAxisLabelColor: '4F6381',
                valAxisLabelFontFace: 'Arial', valAxisLabelFontSize: 10, valAxisLabelColor: '74839B',
                showBorder: false, showTitle: false, showValue: false,
                showCatName: false, showMarker: false, showShadow: false,
                catAxisLineShow: false, valAxisLineShow: false,
                valGridLine: {color: 'E1E7F0', width: .5}}, extra || {});
        }
        function empty(page, message) {
            page.addText(message || 'No records in this view.', {x: .8, y: 3.15, w: 11.7, h: .7, fontSize: 20, color: '74839B', align: 'center', margin: 0});
        }
        function grouped(title, entries, scopeNote) {
            const pages = Math.max(1, Math.ceil(entries.length / 10));
            for (let start = 0; start < pages; start++) {
                const batch = entries.slice(start * 10, start * 10 + 10);
                const page = slide(title + (pages > 1 ? ' · ' + (start + 1) + '/' + pages : ''), scopeNote || view.scope);
                if (!batch.length || !batch.some(([, counts]) => counts.total)) { empty(page); continue; }
                page.addChart(pptx.ChartType.bar, levels.map(level => ({name: level, labels: batch.map(([name]) => short(name, 55)), values: batch.map(([, counts]) => counts[level])})), chartOptions({barDir: 'bar', barGrouping: 'stacked', catAxisLabelPos: 'low', valAxisMinVal: 0, gapSizePct: 70}));
                page.addNotes(batch.map(([name, counts]) => name + ': ' + levels.map(level => level + ' ' + counts[level]).join(', ')));
            }
        }
        function breakdown(title, values) {
            const entries = Object.entries(values).sort((a, b) => b[1] - a[1]);
            const pages = Math.max(1, Math.ceil(entries.length / 10));
            for (let index = 0; index < pages; index++) {
                const batch = entries.slice(index * 10, index * 10 + 10);
                const page = slide(title + (pages > 1 ? ' · ' + (index + 1) + '/' + pages : ''));
                if (!batch.length) { empty(page); continue; }
                page.addChart(pptx.ChartType.bar, [{name: 'Risks', labels: batch.map(([name]) => short(name, 60)), values: batch.map(([, value]) => value)}], chartOptions({chartColors: ['526DD3'], showLegend: false, showValue: true, dataLabelPosition: 'outEnd', dataLabelFormatCode: '0', dataLabelColor: '3D567F', barDir: 'bar', catAxisLabelPos: 'low', valAxisMinVal: 0}));
                page.addNotes(batch.map(([name, count]) => name + ': ' + count));
            }
        }
        const counts = view.snapshot.counts;
        const overview = slide('Enterprise risk analysis', view.scope + ' · Current snapshot');
        const cards = [['Total risks', counts.total, '365FD1'], ...levels.map((level, index) => [level, counts[level], tones[index]])];
        cards.forEach(([label, value, color], index) => {
            const x = .55 + index * 2.47;
            overview.addShape(pptx.ShapeType.roundRect, {x, y: 2.15, w: 2.3, h: 1.6, line: {color: 'E0E6F1', width: .8}, fill: {color: 'FFFFFF'}});
            overview.addText(label, {x: x + .15, y: 2.42, w: 2, h: .3, fontSize: 13, color: '667A96', margin: 0});
            overview.addText(String(value), {x: x + .15, y: 2.95, w: 2, h: .55, fontSize: 33, bold: true, color, margin: 0});
        });
        overview.addText('Risk ratings follow the approved 3 × 3 impact × likelihood matrix.', {x: .65, y: 4.25, w: 12, h: .55, fontSize: 16, color: '4F6381', margin: 0});
        overview.addText('The export reflects the current selection. Charts are editable; department charts follow the current search and sort.', {x: .65, y: 5.1, w: 12, h: .8, fontSize: 13, color: '74839B', margin: 0});
        const distribution = slide('Rating distribution');
        if (!counts.total) empty(distribution, 'No risks recorded in this view.');
        else {
            distribution.addChart(pptx.ChartType.doughnut, [{name: 'Risks', labels: levels, values: levels.map(level => counts[level])}], chartOptions({x: .6, w: 7.3, holeSize: 68, showPercent: true, showValue: false, showCatName: false, dataLabelPosition: 'bestFit', dataLabelFormatCode: '0%', dataLabelColor: '263B60'}));
            levels.forEach((level, index) => {
                distribution.addText(level, {x: 8.5, y: 2.15 + index * .8, w: 2, h: .35, fontSize: 16, color: tones[index], margin: 0});
                distribution.addText(String(counts[level]) + '  ·  ' + Math.round(counts[level] / counts.total * 100) + '%', {x: 10.3, y: 2.15 + index * .8, w: 2.4, h: .35, fontSize: 17, bold: true, color: '263B60', margin: 0});
            });
        }
        grouped('Risk groups', view.groups.map(([name, bucket]) => [name, bucket.counts]));
        grouped('Department comparison', view.departments, view.scope + (view.departmentSearch ? ' · Department search: ' + view.departmentSearch : ''));
        breakdown('Workflow stages', view.snapshot.stages);
        breakdown('Response strategies', view.snapshot.responses);
        await pptx.writeFile({fileName: 'risk-analysis.pptx'});
    }
    window.CgbodAnalysisExport = {powerPoint};
})();
