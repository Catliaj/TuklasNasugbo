// admin-charts.js - shared Chart.js helpers and default theme
(function(){
    'use strict';
    const adminCharts = {};

    // Default theme config
    adminCharts.defaults = {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 600, easing: 'cubicBezier(.2,.6,.2,1)' },
        interaction: { mode: 'nearest', intersect: false },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#111827',
                bodyColor: '#111827',
                bodySpacing: 8,
                borderWidth: 1,
                borderColor: '#e6edf5',
                boxPadding: 6,
                boxShadow: '0 6px 20px rgba(0,0,0,0.08)'
            }
        }
    };

    // Create a gradient from 2 colors (if possible); fall back to single color
    adminCharts.gradient = function(ctx, colorA, colorB) {
        try {
            const g = ctx.createLinearGradient(0, 0, 0, ctx.canvas.height);
            g.addColorStop(0, colorA);
            g.addColorStop(1, colorB || colorA);
            return g;
        } catch(e) { return colorA; }
    };

    // Merge options with defaults sensibly
    function mergeDefaults(baseOptions) {
        const merged = Object.assign({}, adminCharts.defaults, baseOptions || {});
        // plugins tooltip merge
        merged.plugins = Object.assign({}, adminCharts.defaults.plugins, baseOptions?.plugins || {});
        return merged;
    }

    // Create Chart with defaults; config resembles Chart.js config or a dataset/labels object
    adminCharts.createChart = function(ctx, configOrType, dataOrConfig, options){
        if (typeof configOrType === 'string') {
            // Called as createChart(ctx, type, data, options)
            const type = configOrType; const data = dataOrConfig; const opts = options || {};
            const config = { type: type, data: data, options: mergeDefaults(opts) };
            // Allow gradient on datasets
            if (config.data && config.data.datasets) {
                config.data.datasets.forEach((ds, i) => {
                    if (ds.gradient) ds.backgroundColor = adminCharts.gradient(ctx, ds.gradient[0], ds.gradient[1]);
                });
            }
            return new Chart(ctx, config);
        }
        // Called as createChart(ctx, config)
        const config = configOrType;
        config.options = mergeDefaults(config.options || {});
        // apply gradients to datasets where gradient array provided
        if (config.data && config.data.datasets) {
            config.data.datasets.forEach(ds => {
                if (ds.gradient && ctx && ctx.canvas) ds.backgroundColor = adminCharts.gradient(ctx, ds.gradient[0], ds.gradient[1]);
            });
        }
        return new Chart(ctx, config);
    };

    // Expose globally
    window.adminCharts = adminCharts;
})();
