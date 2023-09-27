<script>
export default {
    props: {
        title: {
            type: String,
            default: 'HR'
        },
        breadcumbs: {
            type: Array,
            default: () => []
        }   
    },
    computed: {
        lastBreadcumb() {
            return this.breadcumbs[this.breadcumbs.length - 1]
        }
    }
}
</script>
<template>
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ title }}</h1>
                </div>
                <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item" 
                    v-for="(breadcumb, idx) in breadcumbs" 
                    :key="idx" :class="lastBreadcumb.name == breadcumb.name ? 'active' : ''">
                        <a v-if="lastBreadcumb.name != breadcumb.name" :href="breadcumb.link">{{ breadcumb.name }}
                        </a>
                        <span v-else>{{ breadcumb.name }}</span>
                    </li>
                </ol>
            </div>
            </div>
        </div>
</template>