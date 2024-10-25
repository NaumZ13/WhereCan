<template>
  <div class="datatable container mx-auto p-6 bg-white shadow rounded-lg mt-10 mb-10">
    <div class="mb-4">
      <input
        type="text"
        v-model="search"
        placeholder="Search products..."
        class="w-full p-2 border border-gray-300 rounded-md"
      />
    </div>

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
});

const search = ref('');
const sortBy = ref('');
const sortDirection = ref('asc');
const tableKey = ref(0);

const sortTable = (column) => {
  if (sortBy.value === column) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortBy.value = column;
    sortDirection.value = 'asc';
  }
};
</script>

<style scoped>
.table-auto th, .table-auto td {
  padding: 12px;
}
</style>
