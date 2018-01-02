'use strict';

var API_STATS_ENDPOINT = 'https://api.relliker.com/v1/stats';

Chart.plugins.register({
  afterDraw: function(chart) {
    let noData = false;
    if (chart.data.datasets.length === 0) {
      noData = true;
    } else {
      // Assume we have no data
      noData = true;
      for (let i = 0; i < chart.data.datasets.length; ++i) {
        let dataset = chart.data.datasets[i];
        // If we have data for at least one dataset, we are okay
        if (dataset.data.length > 0) {
          noData = false;
          break;
        }
      }
    }

    if (noData) {
      // No data is present
      var ctx = chart.chart.ctx;
      var width = chart.chart.width;
      var height = chart.chart.height
      chart.clear();

      ctx.save();
      ctx.textAlign = 'center';
      ctx.textBaseline = 'middle';
      ctx.font = "16px normal inherit";
      ctx.fillText('Something went wrong: no data :(',
        width / 2,
        height / 2);
      ctx.restore();
    }
  }
});

function populateGraph(id, labels, datasets) {
  let ctx = document.getElementById(id).getContext('2d');
  let chart = new Chart(ctx, {
      type: 'line',
      data: {
          labels,
          datasets,
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          intersect: false,
        },
        legend: {
          display: false,
        }
      },
  });
}

function fetchStats(stat, id) {
  $.ajax({
    url: API_STATS_ENDPOINT,
    data: {
      type: stat,
    },
    success: function (result) {
      if (result.result && result.result.datasets.length > 0) {
        populateGraph(id, result.result.labels, result.result.datasets);
      } else {
        populateGraph(id, [], [], 'Undefined');
      }
    },
    error: function () {
      populateGraph(id, [], [], 'Undefined');
    },
    datatype: 'json',
  });
}
