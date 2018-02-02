<template>
    <select :id="id" :name="name" :data-placeholder="placeholder" :multiple="multiple">
        <slot></slot>
    </select>
</template>

<script>
    export default {

        props: {
            options: {
                type: Object,
                default: function () {
                    return {};
                }
            },
            data: {},
            value: {},
            placeholder: {},
            name: {},
            id: {},
            multiple: {}
        },

        data: function () {
            return {
                parsed_options: {}
            }
        },

        mounted: function () {
            var vm = this;

            this.parsed_options = this.parseOptions(this.options);

            this.parsed_options.data = this.parseData(this.data);

            if (this.parsed_options.data.length <= 10) {
                this.parsed_options.dropdownCssClass = 'no-search';
            }

            $(this.$el)
                .select2(this.parsed_options)
                .val(this.value)
                .trigger('change')
                .on('change', function (e) {
                    vm.$emit('change');

                    vm.$emit('input', $(vm.$el).val())
                })
                .on('select2:select', function () {
                    vm.$emit('select', $(vm.$el).val());
                })
        },

        methods: {
            parseOptions: function (options) {
                options.templateResult = function (data) {
                    if (data.html) {
                        return data.html;
                    } else {
                        return data.text;
                    }
                };

                options.escapeMarkup = function (m) {
                    return m;
                };

                options.templateSelection = function (data) {
                    return data.text;
                };

                return options;
            },
            parseData: function (data) {
                var parsedData = data;

                if (Netcore.isObject(parsedData)) {
                    parsedData = [];

                    for (var key in data) {
                        if (data.hasOwnProperty(key)) {
                            parsedData.push(data[key]);
                        }
                    }
                }

                return data;
            }
        },

        watch: {
            value: function (value) {
                if (this.multiple) {
                    if (Array.from(value).sort().join(",") !== Array.from($(this.$el).val()).sort().join(",")) {
                        $(this.$el).val(value).trigger('change');
                    }
                } else {
                    $(this.$el).val(value).trigger('change');
                }
            },
            options: function (options) {
                this.parsed_options = this.parseOptions(options);

                this.parsed_options.data = this.data;

                if (this.data.length <= 10) {
                    this.parsed_options.dropdownCssClass = 'no-search';
                }

                $(this.$el).empty().select2(this.parsed_options);
            },
            data: function (data) {
                this.parsed_options.data = this.parseData(data);

                $(this.$el).select2(this.parsed_options)
            }
        },

        destroyed: function () {
            $(this.$el).off().select2('destroy')
        }
    }
</script>

<style>
    /* Hide search box when not needed */
    .no-search .select2-search {
        display: none
    }
</style>