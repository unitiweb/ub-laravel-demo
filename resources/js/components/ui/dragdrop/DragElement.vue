<template>
    <div :draggable="draggable" @dragstart="startDrag($event, elementData)">
        <slot/>
    </div>
</template>

<script>

    export default {
        props: {
            draggable: {
                type: Boolean,
                default: true
            },
            action: {
                type: String
            },
            elementData: {
                type: Object,
                default: () => {}
            },
            effect: {
                type: String,
                default: 'move',
                validator: function (value) {
                    // The value must match one of these strings
                    return ['none', 'copy', 'link', 'move'].indexOf(value) !== -1
                }
            },
            allowed: {
                type: String,
                default: 'move',
                validator: function (value) {
                    // The value must match one of these strings
                    return ['none', 'copy', 'copyLink', 'copyMove', 'link', 'linkMove', 'move', 'all', 'uninitialized'].indexOf(value) !== -1
                }
            }
        },

        methods: {
            startDrag (evt, data) {
                console.log('this.dropEffect', this.effect)
                console.log('startDrag', evt, data)
                evt.dataTransfer.dropEffect = this.effect
                evt.dataTransfer.effectAllowed = this.allowed
                evt.dataTransfer.setData(
                    'text/plain',
                    JSON.stringify({
                        action: this.action,
                        data: data
                    })
                )

                // if (this.action) {
                //     evt.dataTransfer.setData('action', this.action)
                // }
                // if (this.data) {
                //     evt.dataTransfer.setData('data', JSON.stringify(data))
                // }
            }
        }
    }
</script>
