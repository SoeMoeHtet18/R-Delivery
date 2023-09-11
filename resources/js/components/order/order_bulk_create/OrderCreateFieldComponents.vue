<template>
    <tr>
        <td><input v-model="rowData.inputValue" type="text"></td>
        <td>
        <select v-model="rowData.selectedOption">
            <option value="option1">Option 1</option>
            <option value="option2">Option 2</option>
            <option value="option3">Option 3</option>
        </select>
        </td>
        <td>
        <label>
            <input type="radio" v-model="rowData.radioValue" value="radio1"> Radio 1
        </label>
        <label>
            <input type="radio" v-model="rowData.radioValue" value="radio2"> Radio 2
        </label>
        </td>
    </tr>
</template>

<script>
  export default {
    props: {
        rowIndex: String,
    },
  }
</script>