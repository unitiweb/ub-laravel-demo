<template>
    <div @drop="onDrop($event)"
         @dragenter="dragEnter($event)"
         @dragleave="dragLeave($event)"
         @dragend="dragEnd($event)"
         @dragover.prevent
         @dragenter.prevent>
        <div class="drop-zone-top">
            <slot/>
        </div>
    </div>
</template>

<script>

    export default {

        props: {
            opacity: {
                type: Number,
                default: null,
                validator: function (value) {
                    // The value must match one of these strings
                    if (value) {
                        return value >= 0.0 && value <= 1.0
                    }
                    return true
                }
            }
        },

        methods: {
            onDrop (event) {
                // event.preventDefault();
                this.removeClasses(event)
                const data = event.dataTransfer.getData('text/plain')
                this.$emit('dropped', JSON.parse(data))
            },
            dragEnter (event) {
                this.addClasses(event)
            },
            dragLeave (event) {
                this.removeClasses(event)
            },
            dragEnd (event) {
                this.removeClasses(event)
            },
            addClasses (event) {
                if (this.opacity) {
                    event.target.style.opacity = this.opacity
                }
            },
            removeClasses (event) {
                if (this.opacity) {
                    event.target.style.removeProperty('opacity')
                }
            }
        }
    }

</script>
