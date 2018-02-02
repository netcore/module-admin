<template>
    <ul class="dropdown-menu">
        <li v-for="menuItem in item.children"
            :class="{'dropdown-toggle': menuItem.children.length > 0, 'divider': menuItem.type === 'seperator' || menuItem.type === 'empty'}">

            <template v-if="menuItem.type !== 'seperator' && menuItem.type !== 'empty'">
                <a :href="menuItem.url" :target="menuItem.target">
                    <i v-if="menuItem.icon" class="fa" :class="menuItem.icon"></i>
                    <span v-html="menuItem.name"></span>
                </a>
            </template>

            <template v-if="menuItem.children.length">
                <top-menu-child-items :item="menuItem"></top-menu-child-items>
            </template>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "top-menu-child-items",

        props: ['item'],
    }
</script>

<style scoped>

</style>