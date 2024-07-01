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

## Highlights

### Slots Usage
The `DynamicTable` component uses slots to provide an easy way to manipulate the front-end data.

```html
<template #row="{ record, column }">
  <div class="cell" v-if="column.name === 'my_first_column'">
    Krait is awesome!
  </div>
  <div class="cell" v-else>
    {{ record[column.name] ?? 'N/A' }}
  </div>
</template>
```

- The `record` object contains the record values for all columns
- The `column` object contains all column properties (`name`, `fixed`, `label`, etc.)
