## Description
The `krait-ui` DynamicTable component represents the table data on front-end.

## Example

```html
<script setup>
defineProps({
  'filtersForm': {
    type: String,
    required: false,
    default: undefined,
  }
});
</script>

<template>
  <DynamicTable
    apiEndpoint="users-table"
    tableName="users-table"
    :filtersForm="filtersForm"
  >
    <template #row="{ record, column }">
      <div class="cell" v-if="column.name === 'my_first_column'">
        Krait is awesome!
      </div>
      <div class="cell" v-else>
        {{ record[column.name] ?? 'N/A' }}
      </div>
    </template>
  </DynamicTable>
</template>

<style scoped lang="scss"></style>
```
