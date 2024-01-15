<script setup lang="ts">
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import {ref,onMounted} from 'vue'
import axiosIns from "@axios";
import {useRoute} from "vue-router";

// Components


const invoice = ref([])
const route = useRoute()
// 👉 Invoice Description
// ℹ️ Your real data will contain this information
const purchasedProducts = ref([])


const getInvoice = async () => {
  try {
    let response =  await axiosIns.get(`/api/user/get-invoice/${route.query.id}`)
    purchasedProducts.value = response.data
  }catch (e) {
    console.log(e)
  }
}

// document.addEventListener('contextmenu', event => event.preventDefault());

// 👉 Print Invoice
const printInvoice = () => {
  window.print()
}
onMounted(async () => {
  await getInvoice()
})
</script>

<template>
  <section>
    <VRow>
      <VCol
        cols="12"
        md="9"
      >
        <VCard>
          <!-- SECTION Header -->
          <VCardText class="d-flex flex-wrap justify-space-between flex-column flex-sm-row print-row">
            <!-- 👉 Left Content -->
            <div class="ma-sm-4">
              <div class="d-flex align-center mb-6">
                <!-- 👉 Logo -->
                <VNodeRenderer
                  :nodes="themeConfig.app.logo"
                  class="me-3"
                />

                <!-- 👉 Title -->
                <h6 class="font-weight-bold text-capitalize text-h6">
                  Prime Insurance Ghana
                </h6>
              </div>

              <!-- 👉 Address -->
              <p class="mb-0">
                Office 149, 450 South Brand Brooklyn
              </p>
              <p class="my-2">
                San Diego County, CA 91905, USA
              </p>
              <p class="mb-0">
                +1 (123) 456 7891, +44 (876) 543 2198
              </p>
            </div>

            <!-- 👉 Right Content -->
            <div class="mt-4 ma-sm-4">
              <!-- 👉 Invoice ID -->
              <h6 class="font-weight-medium text-h4">
                Voucher #{{ purchasedProducts?.id }}
              </h6>

              <!-- 👉 Issue Date -->
              <p class="my-3">
                <span>Date Issued: </span>
                <span>{{ new Date(purchasedProducts?.date).toLocaleDateString('en-GB') }}</span>
              </p>

              <!-- 👉 Due Date -->
              <p class="mb-0">
                <span>Name: </span>
                <span>{{ purchasedProducts?.user }}</span>
              </p>
              <p class="mb-0">
                <span>Phone Number: </span>
                <span>{{ purchasedProducts?.phone_number }}</span>
              </p>

            </div>
          </VCardText>
          <!-- !SECTION -->

          <VDivider />

          <!-- 👉 Payment Details -->

          <!-- 👉 Table -->
          <VDivider />

          <VTable class="invoice-preview-table">
            <thead>
            <tr>
              <th scope="col">
                ITEM NAME
              </th>
              <th scope="col">
                ITEM CODE
              </th>
              <th
                scope="col"
                class="text-center"
              >
                COST
              </th>
              <th
                scope="col"
                class="text-center"
              >
                QTY
              </th>
              <th
                scope="col"
                class="text-center"
              >
                PRICE
              </th>
            </tr>
            </thead>

            <tbody>
            <tr
              v-for="item in purchasedProducts.items"
              :key="item.name"
            >
              <td class="text-no-wrap">
                {{ item.name }}
              </td>
              <td class="text-no-wrap">
                {{ item.code }}
              </td>
              <td class="text-center">
                 {{ Number(item.price).toLocaleString('en-US', { style: 'currency', currency: 'GHC' }) }}
              </td>
              <td class="text-center">
                {{ item.quantity }}
              </td>
              <td class="text-center">
                {{ (Number(item.price * item.quantity)).toLocaleString('en-US', { style: 'currency', currency: 'GHC' }) }}
              </td>
            </tr>
            </tbody>
          </VTable>

          <VDivider class="mb-2" />

          <!-- Total -->
          <VCardText class="d-flex justify-space-between flex-column flex-sm-row print-row">
            <div class="my-2 mx-sm-4 text-base">
<!--              <div class="d-flex align-center mb-1">-->
<!--                <h6 class="text-base font-weight-medium me-1">-->
<!--                  Prepared By:-->
<!--                </h6>-->
<!--                <span>Sharon Agyei</span>-->
<!--              </div>-->
<!--              <p>Thanks for your business</p>-->
            </div>

            <div class="my-2 mx-sm-4">
              <table>
                <tbody>
                <tr>
                  <td class="text-end">
                    <div class="me-5">

                      <p class="mb-2">
                        Total:
                      </p>
                    </div>
                  </td>

                  <td class="font-weight-medium text-high-emphasis">
                    <p class="mb-2">
                      GHC {{purchasedProducts.total}}
                    </p>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </VCardText>

          <VDivider />

          <VCardText>
            <div class="d-flex mx-sm-4">
<!--              <h6 class="text-base font-weight-medium me-1">-->
<!--                Note:-->
<!--              </h6>-->
<!--              <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance projects. Thank You!</span>-->
            </div>
          </VCardText>
        </VCard>
      </VCol>

      <VCol
        cols="12"
        md="3"
        class="d-print-none"
      >
        <VCard>
          <VCardText>
            <!-- 👉 Send Invoice Trigger button -->

            <VBtn
              block
              variant="tonal"
              color="secondary"
              :disabled="purchasedProducts.status !== 'approved'"
              class="mb-2"
              @click="printInvoice"
            >
              Print
            </VBtn>


            <!-- 👉  Add Payment trigger button  -->
          </VCardText>
        </VCard>
      </VCol>
    </VRow>

  </section>
</template>

<style lang="scss">
.invoice-preview-table {
  --v-table-row-height: 44px !important;
}

@media print {
  .v-theme--dark {
    --v-theme-surface: 255, 255, 255;
    --v-theme-on-surface: 94, 86, 105;
  }

  body {
    background: none !important;
  }

  @page { margin: 0; size: auto; }

  .layout-page-content,
  .v-row,
  .v-col-md-9 {
    padding: 0;
    margin: 0;
  }

  .product-buy-now {
    display: none;
  }

  .v-navigation-drawer,
  .layout-vertical-nav,
  .app-customizer-toggler,
  .layout-footer,
  .layout-navbar,
  .layout-navbar-and-nav-container {
    display: none;
  }

  .v-card {
    box-shadow: none !important;

    .print-row {
      flex-direction: row !important;
    }
  }

  .layout-content-wrapper {
    padding-inline-start: 0 !important;
  }

  .v-table__wrapper {
    overflow: hidden !important;
  }
}
</style>
<route lang="yaml">
meta:
  action: list
  subject: invoice
</route>
