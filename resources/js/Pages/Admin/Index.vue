<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <h1 class="text-xl font-bold">Users with Overdue Books</h1>
        <div class="bg-white">
          <ResponsiveTable :data="overdue_users.data" :headers="headers"></ResponsiveTable>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ResponsiveTable from "@/Components/ResponsiveTable.vue";
</script>

<script>
export default {
  props: ['overdue_users'],
  computed: {
    headers() {
      return {
        id: 'ID',
        name: {label: 'Name', displayAs: (user) => user.name},
        current_borrows_count: "# Borrowing",
        overdue_borrows_count: "# Overdue"
      };
    }
  }
}
</script>
