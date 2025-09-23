<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import { formatDate } from "../../utils/dateFormat";
import CreateEmployee from "../Employees/CreateEmployee.vue";
import ShowAttendance from "./ShowAttendance.vue";
import { Link } from "@inertiajs/vue3";

defineOptions({ layout: SideBar });
defineProps({
    employees: Object,
});


</script>


