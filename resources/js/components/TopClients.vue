<template>
  <div v-if="topClients && topClients.length > 0" class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ $t("dashboard.top_clients_title") }} ({{ year }})
      </h3>
    </div>
    <div class="card-body row">
      <div v-if="topClients[1] && topClients[1].client" class="col-sm-4">
        <div class="leaderboard-card">
          <div class="leaderboard-card__top text-center">
            <h5 class="text-center">
              {{ topClients[1].invoice_total | withCurrency }}
            </h5>
            <span class="badge badge-primary"
              >{{ topClients[1].total_invoice }} {{ $t("sidebar.sales") }}</span
            >
          </div>
          <div class="leaderboard-card__body">
            <div class="text-center">
              <img
                v-if="topClients[1].client.image_path"
                :src="clientImagePath(topClients[1].client.image_path)"
                class="circle-img mb-2"
                alt="User Img"
              />
              <img
                v-else
                src="https://via.placeholder.com/50x50"
                class="circle-img mb-2"
                alt="User Img"
              />
              <h6 class="mb-0">{{ topClients[1].client.name }}</h6>
              <p class="text-muted mb-0">
                {{ topClients[1].client.company_name }}
              </p>
            </div>
          </div>
        </div>
      </div>
      <div v-if="topClients[0] && topClients[0].client" class="col-sm-4">
        <div class="leaderboard-card leaderboard-card--first">
          <div class="leaderboard-card__top text-center">
            <h5 class="text-center">
              {{ topClients[0].invoice_total | withCurrency }}
            </h5>
            <span class="badge badge-primary"
              >{{ topClients[0].total_invoice }} {{ $t("sidebar.sales") }}</span
            >
          </div>
          <div class="leaderboard-card__body">
            <div class="text-center">
              <img
                v-if="topClients[0].client.image_path"
                :src="clientImagePath(topClients[0].client.image_path)"
                class="circle-img mb-2"
                alt="User Img"
              />
              <img
                v-else
                src="https://via.placeholder.com/50x50"
                class="circle-img mb-2"
                alt="User Img"
              />
              <h6 class="mb-0">{{ topClients[0].client.name }}</h6>
              <p class="text-muted mb-0">
                {{ topClients[0].client.company_name }}
              </p>
            </div>
          </div>
        </div>
      </div>
      <div v-if="topClients[2] && topClients[2].client" class="col-sm-4">
        <div class="leaderboard-card">
          <div class="leaderboard-card__top text-center">
            <h5 class="text-center">
              {{ topClients[2].invoice_total | withCurrency }}
            </h5>
            <span class="badge badge-primary"
              >{{ topClients[2].total_invoice }} {{ $t("sidebar.sales") }}</span
            >
          </div>
          <div class="leaderboard-card__body">
            <div class="text-center">
              <img
                v-if="topClients[2].client.image_path"
                :src="clientImagePath(topClients[2].client.image_path)"
                class="circle-img mb-2"
                alt="User Img"
              />
              <img
                v-else
                src="https://via.placeholder.com/50x50"
                class="circle-img mb-2"
                alt="User Img"
              />
              <h6 class="mb-0">{{ topClients[2].client.name }}</h6>
              <p class="text-muted mb-0">
                {{ topClients[2].client.company_name }}
              </p>
            </div>
          </div>
        </div>
      </div>
      <div v-if="topClients && topClients.length > 3" class="table-responsive">
        <table v-show="topClients.length > 3" class="clients-table table mb-0">
          <tbody>
            <tr v-for="index in 2" :key="index">
              <td>
                <div
                  v-if="topClients[index + 2] && topClients[index + 2].client"
                  class="d-flex align-items-center"
                >
                  <img
                    v-if="topClients[index + 2].client.image_path"
                    :src="
                      clientImagePath(topClients[index + 2].client.image_path)
                    "
                    class="circle-img circle-img--small mr-2"
                    loading="lazy"
                  />
                  <img
                    v-else
                    src="https://via.placeholder.com/50x50"
                    class="circle-img circle-img--small mr-2"
                    loading="lazy"
                  />
                  <div class="user-info__basic">
                    <h6 class="mb-0">
                      {{ topClients[index + 2].client.name }}
                    </h6>
                    <p class="text-muted mb-0">
                      {{ topClients[index + 2].client.company_name }}
                    </p>
                  </div>
                </div>
              </td>
              <td v-if="topClients[index + 2]">
                <div class="d-flex align-items-baseline">
                  {{ topClients[index + 2].total_invoice }}
                  {{ $t("sidebar.sales") }}
                </div>
              </td>
              <td v-if="topClients[index + 2]">
                {{ topClients[index + 2].invoice_total | withCurrency }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "TopClients",
  data: () => ({
    topClients: "",
    year: new Date().getFullYear(),
  }),

  created() {
    this.getTopClients();
  },
  methods: {
    // get top clients
    async getTopClients() {
      const { data } = await axios.get(
        window.location.origin + "/api/dashboard/top-clients"
      );
      this.topClients = data;
    },

    // get image
    clientImagePath(imageName) {
      return window.location.origin + "/images/clients/" + imageName;
    },
  },
};
</script>
