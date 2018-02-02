<template>
    <ul class="px-nav-dropdown-menu" :class="{'px-open': item.child_active}">
        <li v-for="menuItem in item.children" class="px-nav-item"
            :class="{'px-nav-dropdown': menuItem.children.length > 0, 'active': menuItem.active, 'px-open': menuItem.child_active}">
            <template v-if="menuItem.type == 'separator' || menuItem.type == 'empty'">
                <hr>
            </template>
            <template v-else>
                <a :href="menuItem.url" :target="menuItem.target">
                    <i v-if="menuItem.icon" class="px-nav-icon fa" :class="menuItem.icon"></i>
                    <span class="px-nav-label" v-html="menuItem.name"></span>
                </a>
                <template v-if="menuItem.children">
                    <left-menu-child-items :item="menuItem"></left-menu-child-items>
                </template>
            </template>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "left-menu-child-items",

        props: ['item'],
    }
</script>

<style scoped>

</style>