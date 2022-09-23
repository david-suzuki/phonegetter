<template>
  <!--begin::Mixed Widget 1-->
  <div class="card card-xxl-stretch" style="margin-top:40px">
    <!--begin::Body-->
    <div class="card-body p-0">
      <!--begin::Stats-->
      <div class="card-p mt-n20 position-relative">
        <!--begin::Row-->
        <div class="row m-0">
          
          <div class="col bg-light-warning px-6 py-8 rounded-2 me-7 mb-7">
            <span class="text-warning fw-bold fs-1" style="float:right; margin-top:15px; margin-right:15px;">{{ loginNumber }}</span>
            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
              <inline-svg src="/media/icons/duotone/Communication/Add-user.svg" />
            </span>
            <a href="#" class="text-warning fw-bold fs-6">
              {{ translate("yesterdayLoginCounts") }}
            </a>
          </div>

          <div class="col bg-light-primary px-6 py-8 rounded-2 mb-7">
            <span class="text-primary fw-bold fs-1" style="float:right; margin-top:15px; margin-right:15px;">{{ historyNumber }}</span>
            <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
              <inline-svg
                src="/media/icons/duotone/Media/Equalizer.svg"
              />
            </span>
            <a href="#" class="text-primary fw-bold fs-6">
              {{ translate("yesterdayPickedNumbers") }}
            </a>
          </div>
          
        </div>
        <!--end::Row-->
      </div>
      <!--end::Stats-->
      <!--end::Body-->
    </div>
    <!--end::Mixed Widget 1-->
  </div>
</template>

<script>
import { defineComponent, onMounted } from "vue";
import { MenuComponent } from "@/assets/ts/components/MenuComponent.ts";
import { getCSSVariableValue } from "@/assets/ts/_utils";
import axios from "axios";
import { useI18n } from "vue-i18n";

export default defineComponent({
  name: "widget-1",
  components: {
  },
  props: {
    color: String
  },
  setup() {
    const { t, te } = useI18n();

    const labelColor = getCSSVariableValue("--bs-gray-500");
    const borderColor = getCSSVariableValue("--bs-gray-200");

    const color = getCSSVariableValue("--bs-danger");
    const strokeColor = "#cb1e46";

    const chartOptions = {
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
        },
        dropShadow: {
          enabled: true,
          enabledOnSeries: undefined,
          top: 5,
          left: 0,
          blur: 3,
          color: strokeColor,
          opacity: 0.5
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
        opacity: 0
      },
      stroke: {
        curve: "smooth",
        show: true,
        width: 3,
        colors: [strokeColor]
      },
      xaxis: {
        categories: ["Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug"],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: false,
          style: {
            colors: labelColor,
            fontSize: "12px"
          }
        },
        crosshairs: {
          show: false,
          position: "front",
          stroke: {
            color: borderColor,
            width: 1,
            dashArray: 3
          }
        }
      },
      yaxis: {
        min: 0,
        max: 80,
        labels: {
          show: false,
          style: {
            colors: labelColor,
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
            return "$" + val + " thousands";
          }
        },
        marker: {
          show: false
        }
      },
      markers: {
        colors: [color],
        strokeColor: [strokeColor],
        strokeWidth: 3
      }
    };

    const series = [
      {
        name: "Net Profit",
        data: [30, 45, 32, 70, 40, 40, 40]
      }
    ];

    onMounted(() => {
      MenuComponent.reinitialization();
    });

    const translate = text => {
      if (te(text)) {
        return t(text);
      } else {
        return text;
      }
    };

    return {
      series,
      chartOptions,
      translate
    };
  },

  mounted() {
    const token = localStorage.getItem('id_token');
    const authHeader = { Authorization: 'Bearer ' + token };

    axios
    .get("http://localhost:8000/dashboard", { headers: authHeader })
    .then((response) => { 
      this.loginNumber = response.data.login_number;
      this.historyNumber = response.data.history_number;
    }).catch((err) => {
      console.log(err);
    });
  },

  data() {
    return {
      loginNumber: 0,
      historyNumber: 0
    }
  }

});
</script>
