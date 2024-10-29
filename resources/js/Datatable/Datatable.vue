<template>
  <div class="datatable container mx-auto p-6 bg-white shadow rounded-lg mb-10">
    <div class="overflow-x-auto">
      <table :key="tableKey" class="table-auto w-full text-left bg-gray-100 rounded-lg">
        <thead class="bg-gray-200">
          <tr>
            <th v-for="column in columns" :key="column.key" @click="sortTable(column.key)" class="px-4 py-2 cursor-pointer">
              {{ column.label }}
              <span v-if="column.sortable">▼</span>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in data" :key="row.id" class="border-b hover:bg-gray-50">
            <td v-for="column in columns" :key="column.key" class="px-4 py-2">
              <!-- Named slot for custom cell rendering -->
              <slot :name="column.key" :row="row" :column="column">
                <!-- Fallback content if no slot is provided -->
                {{ row[column.key] }}
              </slot>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="pagination mt-4 flex justify-center space-x-2">
          <button 
            @click="$emit('changePage', pagination.currentPage - 1)" 
            :disabled="pagination.currentPage === 1"
            class="px-3 py-1 bg-gray-200 rounded-md"
          >
            Previous
          </button>
          
          <span>Page {{ pagination.currentPage }} of {{ pagination.lastPage }}</span>
          
          <button 
            @click="$emit('changePage', pagination.currentPage + 1)" 
            :disabled="pagination.currentPage === pagination.lastPage"
            class="px-3 py-1 bg-gray-200 rounded-md"
          >
            Next
          </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
  columns: {
    type: Array,
    default: () => [],
  },
  data: {
    type: Array,
    default: () => [],
  },
  pagination: {
    type: Object,
    default: () => ({
      currentPage: 1,
      lastPage: 1,
    }),
  },
});
const tableKey = ref(0);

</script>

<style scoped>
.table-auto th, .table-auto td {
  padding: 12px;
}
</style>
