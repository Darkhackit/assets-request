<script setup>
import {ref,watch} from "vue";
import axios from "@axios";
import {debounce} from 'lodash'


const overlay = ref(false)
const searchQuery = ref('')
const selectedStatus = ref()
const rowPerPage = ref(10)
const currentPage = ref(1)
const totalPage = ref(1)
const totalInvoices = ref(0)
const invoices = ref([])
const selectedRows = ref([])
const selectAllInvoice = ref(false)
const showStatus = ref(false)
const statusDetails = ref("")
const addModal = ref(false)
const updateModal = ref(false)
const errors = ref([])
const error = ref([])
const firstIndex = ref(0)
const selectAction = ref("")
const page = ref(1)
const links = ref(0)
const lastIndex = ref(0)
const exportData = ref("")
const search = ref()
const loadPermissions = ref(false)
const permissions = ref([])
const form = ref({
  name:'',
  password:'',
  email:'',
  password_confirmation:'',
  role:[]
})
const table = ref(null)
const ed_form = ref({
  name:'',
  password:'',
  email:'',
  password_confirmation:'',
  role:[],
  id:''
})
const processing = ref(false)
const ed_processing = ref(false)
const addData = async () => {
  processing.value = true
  try {
    await axios.post(`/api/user/employee`,form.value)
    form.value.name = ""
    form.value.email = ""
    form.value.password = ""
    form.value.password_confirmation = ""
    form.value.role = []
    processing.value = false
    addModal.value = false
    showStatus.value = true
    statusDetails.value = "Employee added Successfully"
    await getData()
  }catch (e) {
    if (e.response.status === 422) {
      errors.value = e.response.data.errors
    }
    processing.value = false
  }
}
const clearErrors = (val) => {
  delete errors.value[val]
  form.value.name = form.value.name.toUpperCase()
}
const clearError = (val) => {
  delete error.value[val]
  ed_form.value.name = ed_form.value.name.toUpperCase()
}

const getData = async () => {
  const params = {
    q: searchQuery.value.toUpperCase(),
    status: selectedStatus.value,
    perPage: rowPerPage.value,
    page:currentPage.value,
    currentPage: currentPage.value,
  }
  try {
    overlay.value = true
    let response = await axios.get('/api/user/employee', { params })
    overlay.value = false
    invoices.value = response.data.employees.data
    totalPage.value = response.data.employees.total
    totalInvoices.value = response.data.employees.total
    currentPage.value = response.data.employees.current_page
    firstIndex.value = response.data.employees.from
    lastIndex.value = response.data.employees.last_page
    links.value = response.data.employees.links.length
  }catch (e) {
    console.log(e.response)
    overlay.value = false
  }
}

watch(() => currentPage.value,async () => {
  await getData()
})
watch(() => rowPerPage.value,async () => {
  await getData()
})

const showData = async (id) => {
  try {
    overlay.value = true
    let response = await axios.get(`/api/user/employee/${id}`)
    ed_form.value.name = response.data.name
    ed_form.value.email = response.data.email
    ed_form.value.role = response.data.role
    ed_form.value.id = response.data.id
    updateModal.value = true
    console.log(response)
    overlay.value = false
  }catch (e){
    overlay.value = false
    console.log(e.response)
  }
}
const sendPassword = async (id) => {
  try {
    overlay.value = true
    await axios.get(`/api/user/employee/send_mail/${id}`)
    showStatus.value = true
    statusDetails.value = "Email Sent Successfully"
    overlay.value = false
    await getData()
    selectAction.value = ""
  }catch (e) {
    selectAction.value = ""
    overlay.value = false
  }
}
const sendMail = async () => {
  try {
    overlay.value = true
    await axios.get(`/api/user/employee/sendAll`)
    showStatus.value = true
    statusDetails.value = "Email Sent Successfully"
    overlay.value = false
    await getData()
    selectAction.value = ""
  }catch (e) {
    selectAction.value = ""
    overlay.value = false
  }
}

const updateData = async () => {
  try {
    await axios.patch(`/api/user/employee/${ed_form.value.id}`,ed_form.value)
    ed_form.value.name = ""
    ed_form.value.id = ""
    ed_form.value.permissions = []
    updateModal.value = false
    showStatus.value = true
    statusDetails.value = "Employee updated Successfully"
    await getData()
  }catch (e) {
    console.log(e.response)
    if (e.response.status === 422) {
      error.value = e.response.data.errors
    }
  }
}
watch(() => selectAction.value, async () => {
  if(selectAction.value === "Delete") {
    if (window.confirm("Are you sure?")) {
      await deleteDate()
    }
  }
})
const deleteDate = async () => {
  try {
    await axios.post(`/api/user/employee/delete`,{id:selectedRows.value})
    showStatus.value = true
    statusDetails.value = "Role Deleted Successfully"
    await getData()
    selectAction.value = ""
  }catch (e) {
    selectAction.value = ""
  }
}


// 👉 Add/Remove all checkbox ids in/from array
const selectUnselectAll = () => {
  selectAllInvoice.value = !selectAllInvoice.value
  if (selectAllInvoice.value) {
    invoices.value.forEach(invoice => {
      if (!selectedRows.value.includes(`${ invoice.id }`))
        selectedRows.value.push(`${ invoice.id }`)
    })
  } else {
    selectedRows.value = []
  }
}
const columns = [
  {title: "ID", dataKey: "id"},
  {title: "Name", dataKey: "name"},
  {title: "Permissions", dataKey: "permissions"},
  {title: "Users", dataKey: "users"},
];


// 👉 watch if checkbox array is empty all checkbox should be unchecked
watch(selectedRows, () => {
  if (!selectedRows.value.length)
    selectAllInvoice.value = false
}, { deep: true })

const addRemoveIndividualCheckbox = checkID => {
  if (selectedRows.value.includes(checkID)) {
    const index = selectedRows.value.indexOf(checkID)

    selectedRows.value.splice(index, 1)
  } else {
    selectedRows.value.push(checkID)
    selectAllInvoice.value = true
  }
}
watch(search,() => {
  getPermissions()
})
const getPermissions = debounce(async ()=> {
  if (search.value === "") return
  loadPermissions.value = true
  try {
    let response = await axios.get(`/api/user/role/name/${search.value}`)
    console.log(response)
    permissions.value = response.data
    loadPermissions.value = false
  }catch (e) {
    loadPermissions.value = false
  }
},1000)

onMounted(async () => {
  await getData()
})
</script>

<template>
  <div>
    <section v-if="invoices">
      <!-- 👉 Invoice Filters  -->

      <VCard id="invoice-list">
        <VCardText class="d-flex align-center flex-wrap gap-4">
          <!-- 👉 Actions  -->
          <div class="me-3" v-if="$can('delete','employee')">
            <VSelect
              density="compact"
              label="Actions"
              :items="['Delete']"
              v-model="selectAction"
              class="invoice-list-actions"
              :disabled="!selectedRows.length"
            />
          </div>

          <VSpacer />

          <div class="d-flex align-center flex-wrap gap-4">
            <!-- 👉 Search  -->
            <div class="invoice-list-search" v-if="$can('list','employee')" >
              <VTextField
                v-model="searchQuery"
                placeholder="Search Employee"
                density="compact"
              />
            </div>

            <!-- 👉 Create invoice -->
            <VBtn v-if="$can('list','employee')"
                  prepend-icon="mdi-search"
                  @click.prevent="getData"
            >
              Search
            </VBtn>
            <VBtn v-if="$can('add','employee')"
                  @click.prevent="addModal = true"
                  prepend-icon="mdi-plus"
            >
              Create Employee
            </VBtn>
            <VBtn v-if="$can('add','employee')"
                  @click.prevent="sendMail"
                  prepend-icon="mdi-email"
            >
              send credentials
            </VBtn>
          </div>
        </VCardText>

        <VDivider />

        <!-- SECTION Table -->
        <VTable ref="table" class="text-no-wrap table-header-bg rounded-0">
          <!-- 👉 Table head -->
          <thead>
          <tr>
            <!-- 👉 Check/Uncheck all checkbox -->
            <th
              scope="col"
              class="text-center"
            >
              <div style="width: 1rem;">
                <VCheckbox
                  :model-value="selectAllInvoice"
                  :indeterminate="(invoices.length !== selectedRows.length) && !!selectedRows.length"
                  @click="selectUnselectAll"
                />
              </div>
            </th>
            <th scope="col">
              #ID
            </th>
            <th scope="col">
              NAME
            </th>
            <th
              scope="col"
            >
              EMAIL
            </th>
            <th scope="col">
              ROLES
            </th>
            <th scope="col" >
              ACTIONS
            </th>
          </tr>
          </thead>
          <!-- 👉 Table Body -->
          <tbody>
          <tr
            v-for="invoice in invoices"
            :key="invoice.id"
          >
            <!-- 👉 Individual checkbox -->
            <td>
              <div style="width: 1rem;">
                <VCheckbox
                  :id="`${invoice.id}`"
                  :model-value="selectedRows.includes(`${invoice.id}`)"
                  @click="addRemoveIndividualCheckbox(`${invoice.id}`)"
                />
              </div>
            </td>

            <!-- 👉 Id -->
            <td>

              #{{ invoice.id }}
            </td>

            <!-- 👉 Trending -->
            <td class="">
              {{invoice.name}}
            </td>

            <!-- 👉 Client Avatar and Email -->
            <td>
              {{invoice.email}}
            </td>

            <!-- 👉 total -->
            <td class="">
              <div >
                {{invoice.role}}
              </div>
            </td>

            <!-- 👉 Actions -->
            <td style="width: 8rem;" >
              <!--             <IconBtn @click.prevent="deleteDate(invoice.id)" >-->
              <!--               <VIcon icon="mdi-delete-outline" />-->
              <!--             </IconBtn>-->

              <IconBtn @click.prevent="showData(invoice.id)" >
                <VIcon icon="mdi-edit-outline" />
              </IconBtn>
              <IconBtn @click.prevent="sendPassword(invoice.id)" >
                <VIcon icon="mdi-email-outline" />
              </IconBtn>
            </td>
          </tr>
          </tbody>

          <!-- 👉 table footer  -->
          <tfoot v-show="!invoices.length">
          <tr>
            <td
              colspan="8"
              class="text-center text-body-1"
            >
              No data available
            </td>
          </tr>
          </tfoot>
        </VTable>
        <!-- !SECTION -->

        <VDivider />

        <!-- SECTION Pagination -->
        <VCardText class="d-flex flex-wrap justify-end gap-4 pa-2">
          <!-- 👉 Rows per page -->
          <div
            class="d-flex align-center me-3"
            style="width: 171px;"
          >
            <span class="text-no-wrap me-3">Rows per page:</span>
            <VSelect
              v-model="rowPerPage"
              density="compact"
              variant="plain"
              class="mt-n4"
              :items="[5,10, 20, 30, 50,100,200,500,1000,5000]"
            />
          </div>

          <!-- 👉 Pagination and pagination meta -->
          <div class="d-flex align-center">
            <h6 class="text-sm font-weight-regular">
              {{ `${ firstIndex }-${ lastIndex } of ${ totalInvoices }` }}
            </h6>

            <VPagination
              v-model="currentPage"
              size="small"
              :total-visible="1"
              :length="totalPage"
              @next="selectedRows = []"
              @prev="selectedRows = []"
            />
          </div>
        </VCardText>
        <!-- !SECTION -->
      </VCard>
    </section>
    <!--   add data-->
    <VDialog
      v-model="addModal"
      persistent
      max-width="600"
    >
      <!-- Dialog Content -->
      <VCard title="Add Employee">
        <DialogCloseBtn
          variant="text"
          size="small"
          @click="addModal = false"
        />

        <VCardText>
          <VRow>
            <VCol
              cols="12"
            >
              <VTextField

                v-model="form.name"
                :readonly="processing"
                label="Name"
                @input="clearErrors('name')"
                :class="{'v-field--error': errors?.name}"
              />
              <small style="color: #ff4c20" v-if="errors.name">{{errors.name[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextField

                v-model="form.email"
                :readonly="processing"
                label="Email"
                @input="clearErrors('email')"
                :class="{'v-field--error': errors?.email}"
              />
              <small style="color: #ff4c20" v-if="errors.name">{{errors.email[0]}}</small>
            </VCol>
            <VCol cols="12">
              <VAutocomplete
                v-model="form.role"
                :readonly="processing"
                v-model:search="search"
                :loading="loadPermissions"
                label="Roles"
                :items="permissions"
                @input="clearErrors('permissions')"
                :class="{'v-field--error': errors?.role}"
                chips
                closable-chips
              />
              <small style="color: #ff4c20" v-if="errors.role">{{errors.role[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextField
                type="password"
                v-model="form.password"
                :readonly="processing"
                label="Password"
                @input="clearErrors('password')"
                :class="{'v-field--error': errors?.password}"
              />
              <small style="color: #ff4c20" v-if="errors.password">{{errors.password[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextField
                type="password"
                v-model="form.password_confirmation"
                :readonly="processing"
                label="Password Confirmattion"
                @input="clearErrors('password_confirmation')"
                :class="{'v-field--error': errors?.password_confirmation}"
              />
              <small style="color: #ff4c20" v-if="errors.password_confirmation">{{errors.password_confirmation[0]}}</small>
            </VCol>
          </VRow>
        </VCardText>

        <VCardActions>
          <VSpacer />
          <VBtn
            color="error"
            @click="addModal = false"
          >
            Close
          </VBtn>
          <VBtn
            @click.prevent="addData"
            :disabled="processing"
            :loading="processing"
            color="success"
          >
            Save
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <!--   end data-->
    <!--   edit data-->
    <VDialog
      v-model="updateModal"
      persistent
      max-width="600"
    >
      <!-- Dialog Content -->
      <VCard title="Update Employee">
        <DialogCloseBtn
          variant="text"
          size="small"
          @click="updateModal = false"
        />

        <VCardText>
          <VRow>
            <VCol
              cols="12"
            >
              <VTextField
                v-model="ed_form.name"
                :readonly="ed_processing"
                label="Name"
                @input="clearError('name')"
                :class="{'v-field--error': error?.name}"
              />
              <small style="color: #ff4c20" v-if="error.name">{{error.name[0]}}</small>
            </VCol>
            <VCol
              cols="12"
            >
              <VTextField

                v-model="ed_form.email"
                :readonly="processing"
                label="Email"
                @input="clearError('email')"
                :class="{'v-field--error': error?.email}"
              />
              <small style="color: #ff4c20" v-if="error.name">{{error.email[0]}}</small>
            </VCol>
            <VCol cols="12">
              <VAutocomplete
                v-model="ed_form.role"
                :readonly="processing"
                v-model:search="search"
                :loading="loadPermissions"
                label="Roles"
                :items="permissions"
                @input="clearError('role')"
                :class="{'v-field--error': error?.role}"
                chips
                closable-chips
              />
              <small style="color: #ff4c20" v-if="error.role">{{error.role[0]}}</small>
            </VCol>
          </VRow>
        </VCardText>

        <VCardActions>
          <VSpacer />
          <VBtn
            color="error"
            @click="updateModal = false"
          >
            Close
          </VBtn>
          <VBtn
            @click.prevent="updateData"
            :disabled="ed_processing"
            :loading="ed_processing"
            color="success"
          >
            update
          </VBtn>
        </VCardActions>
      </VCard>
    </VDialog>
    <!--   edit data-->
    <VSnackbar
      v-model="showStatus"
      location="top left"
      variant="flat"
      color="success"
    >
      {{ statusDetails }}
    </VSnackbar>
    <v-overlay
      persistent
      :model-value="overlay"
      class="align-center justify-center"
    >
      <v-progress-circular
        color="primary"
        indeterminate
        size="64"
      ></v-progress-circular>
    </v-overlay>
  </div>
</template>

<style lang="scss">
#invoice-list {
  .invoice-list-actions {
    inline-size: 8rem;
  }

  .invoice-list-search {
    inline-size: 12rem;
  }
}
</style>
<route lang="yaml">
meta:
  action: list
  subject: employee
</route>
