<template>
  <!--begin::Mixed Widget 7-->
  <div class="card card-xxl-stretch-50 mb-5 mb-xl-8">
    <!--begin::Body-->
    <div class="card-body d-flex flex-column p-0">
      <!--begin::Stats-->
      <div class="flex-grow-1 card-p pb-0">
        <div class="d-flex flex-stack flex-wrap">
          <div class="me-2">
            <a href="#" class="text-dark text-hover-primary fw-bolder fs-3"
              >{{ translate("weeklyReport") }}</a
            >

            <div class="text-muted fs-7 fw-bold">
              {{ translate("pickedAmount") }}
            </div>
          </div>

          <div class="fw-bolder fs-3 text-primary">
            {{ translate("per1Week") }}
          </div>
        </div>
      </div>
      <!--end::Stats-->

      <!--begin::Chart-->
      <apexchart
        class="mixed-widget-7-chart card-rounded-bottom"
        :options="chartOptions"
        :series="series"
        type="area"
        width="100%"
        height="150"
      ></apexchart>
      <!--end::Chart-->
    </div>
    <!--end::Body-->
  </div>
  <!--end::Mixed Widget 7-->
</template>

<script>
import { defineComponent } from "vue";
import { useStore } from "vuex";
import axios from "axios";
import { useI18n } from "vue-i18n";

export default defineComponent({
  name: "widget-7",
  setup() {
    const store = useStore();
    const { t, te } = useI18n();

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    return {
      translate
    };
  },

  mounted() {
    const token = localStorage.getItem('id_token');
    const authHeader = { Authorization: 'Bearer ' + token };

    axios
    .get("http://localhost:8000/dashboard/chart", { headers: authHeader })
    .then((response) => { 
      this.series[0].data = response.data;
      this.chartOptions = {
        chart: {
          fontFamily: "inherit",
          type: "area",
          toolbar: {
            show: false
          },
          zoom: {
            enabled: false
          },
          sparkline: {
            enabled: true
          }
        },
        plotOptions: {},
        legend: {
          show: false
        },
        dataLabels: {
          enabled: false
        },
        fill: {
          type: "solid",
          opacity: 1
        },
        stroke: {
          curve: "smooth",
          show: true,
          width: 3,
          colors: ["#27a5f7"]
        },
        xaxis: {
          categories: ["Mon", "Tue", "Wen", "Thu", "Fri", "Sat", "Sun"],
          axisBorder: {
            show: false
          },
          axisTicks: {
            show: false
          },
          labels: {
            show: true,
            style: {
              colors: "#ff4500",
              fontSize: "12px"
            }
          },
          crosshairs: {
            show: false,
            position: "front",
            stroke: {
              color: "#ff4500",
              width: 1,
              dashArray: 3
            }
          },
          tooltip: {
            enabled: false
          }
        },
        yaxis: {
          min: 0,
          max: 10,
          labels: {
            show: false,
            style: {
              colors: "#ff4500",
              fontSize: "12px"
            }
          }
        },
        states: {
          normal: {
            filter: {
              type: "none",
              value: 0
            }
          },
          hover: {
            filter: {
              type: "none",
              value: 0
            }
          },
          active: {
            allowMultipleDataPointsSelection: false,
            filter: {
              type: "none",
              value: 0
            }
          }
        },
        tooltip: {
          style: {
            fontSize: "12px"
          },
          y: {
            formatter: function(val) {
              return val;
            }
          }
        },
        colors: ["#ecf8ff"],
        markers: {
          colors: ["#ecf8ff"],
          strokeColor: ["#27a5f7"],
          strokeWidth: 3
        }
      };
    }).catch((err) => {
      console.log(err);
    });
  },

  data() {
    return {
      series: [{
        name: "Phone Numbers",
        data: []
      }],
      chartOptions: {}
    }
  }
});
</script>
