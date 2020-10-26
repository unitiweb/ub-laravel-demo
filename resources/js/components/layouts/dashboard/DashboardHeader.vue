<template>
    <section>
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold leading-tight text-gray-900">
                    {{ title }}
                </h1>
            </div>
        </header>
        <main>
            <div class="max-w-7xl mx-auto py-2 sm:px-6 lg:px-8">
                <!-- Replace with your content -->
                <div class="px-2 py-2 sm:px-0">
<!--                    <div class="border-4 border-dashed border-gray-200 rounded-lg h-96"></div>-->
                </div>
                <!-- /End replace -->
            </div>
        </main>
    </section>
</template>

<script>
    export default {
        props: {
            title: {
                type: String
            }
        }
    }
</script>
