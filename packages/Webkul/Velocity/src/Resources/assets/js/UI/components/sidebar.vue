<template>
    <!-- categories list -->
    <nav
        :id="id"
        @mouseover="remainBar(id)"
        :class="`sidebar ${addClass ? addClass : ''}`"
        v-if="slicedCategories && slicedCategories.length > 0">

        <ul type="none">
            <li
                :key="categoryIndex"
                :id="`category-${category.id}`"
                class="category-content cursor-pointer"
                @mouseout="toggleSidebar(id, $event, 'mouseout')"
                @mouseover="toggleSidebar(id, $event, 'mouseover')"
                v-for="(category, categoryIndex) in slicedCategories">

                <a
                    :href="`${$root.baseUrl}/${category.slug}`"
                    :class="`category unset ${(category.children.length > 0) ? 'fw6' : ''}`">

                    <div
                        class="category-icon"
                        @mouseout="toggleSidebar(id, $event, 'mouseout')"
                        @mouseover="toggleSidebar(id, $event, 'mouseover')">

                        <img
                            v-if="category.category_icon_path"
                            :src="`${$root.baseUrl}/storage/${category.category_icon_path}`"
                            width="20" height="20" />
                    </div>

                    <span class="category-title">{{ category['name'] }}</span>

                    <i
                        class="rango-arrow-right pr15 float-right"
                        @mouseout="toggleSidebar(id, $event, 'mouseout')"
                        @mouseover="toggleSidebar(id, $event, 'mouseover')"
                        v-if="category.children.length && category.children.length > 0">
                    </i>
                </a>

                <div
                    class="sub-category-container"
                    v-if="category.children.length && category.children.length > 0">

                    <div
                        @mouseout="toggleSidebar(id, $event, 'mouseout')"
                        @mouseover="remainBar(`sidebar-level-${sidebarLevel+categoryIndex}`)"
                        :class="`sub-categories sub-category-${sidebarLevel+categoryIndex} cursor-default sub-categories-2`">

                        <nav
                            class="sidebar"
                            :id="`sidebar-level-${sidebarLevel+categoryIndex}`"
                            @mouseover="remainBar(`sidebar-level-${sidebarLevel+categoryIndex}`)">

                            <ul type="none">
                                <li
                                    @mouseout="toggleSidebar(id, $event, 'mouseout')"
                                    @mouseover="toggleSidebar(id, $event, 'mouseover')"
                                    :id="`category-${subCategoryIndex}-${categoryIndex}`"
                                    :key="`${subCategoryIndex}-${categoryIndex}`"
                                    v-for="(subCategory, subCategoryIndex) in category.children">

                                    <a
                                        :id="`sidebar-level-link-2-${subCategoryIndex}`"
                                        :href="`${$root.baseUrl}/${category.slug}/${subCategory.slug}`"
                                        :class="`category sub-category unset ${(subCategory.children.length > 0) ? 'fw6' : ''}`">

                                        <div
                                            class="category-icon"
                                        >

                                            <img
                                                v-if="subCategory.category_icon_path"
                                                :src="`${$root.baseUrl}/storage/${subCategory.category_icon_path}`" />
                                        </div>
                                        <span class="category-title">{{ subCategory['name'] }}</span>

                                        <i
                                                class="rango-arrow-right pr15 float-right"
                                                @mouseout="toggleSidebar(id, $event, 'mouseout')"
                                                @mouseover="toggleSidebar(id, $event, 'mouseover')"
                                                v-if="subCategory.children.length && subCategory.children.length > 0">
                                        </i>

                                    </a>
                                    <div :class="`sub-categories sub-category-${sidebarLevel+subCategoryIndex} cursor-default sub-categories-3`">
                                        <div
                                                class="sub-category-container ulyana"
                                                v-if="subCategory.children && subCategory.children.length > 0">

                                            <div
                                                    @mouseout="toggleSidebar(id, $event, 'mouseout')"
                                                    @mouseover="remainBar(`sidebar-level-${sidebarLevel+subCategoryIndex}-3`)"
                                                    :class="`sub-category cursor-default`">

                                                <nav
                                                        class="sidebar"
                                                        :id="`sidebar-level-${sidebarLevel+subCategoryIndex}-3`"
                                                        @mouseover="remainBar(`sidebar-level-${sidebarLevel+subCategoryIndex}-3`)">

                                                    <ul type="none">
                                                        <li
                                                                @mouseout="toggleSidebar(id, $event, 'mouseout')"
                                                                @mouseover="toggleSidebar(id, $event, 'mouseover')"
                                                                :id="`category-${childSubCategoryIndex}-${subCategoryIndex}-${categoryIndex}`"
                                                                :key="`${childSubCategoryIndex}-${subCategoryIndex}-${categoryIndex}`"
                                                                v-for="(childSubCategory, childSubCategoryIndex) in subCategory.children">

                                                            <a
                                                                    :id="`sidebar-level-link-3-${childSubCategoryIndex}`"
                                                                    :href="`${$root.baseUrl}/${category.slug}/${subCategory.slug}/${childSubCategory.slug}`"
                                                                    :class="`category sub-category unset ${(childSubCategory.children.length > 0) ? 'fw6' : ''}`">

                                                                <div
                                                                        class="category-icon"
                                                                >

                                                                    <img
                                                                            v-if="childSubCategory.category_icon_path"
                                                                            :src="`${$root.baseUrl}/storage/${childSubCategory.category_icon_path}`" />
                                                                </div>
                                                                <span class="category-title">{{ childSubCategory.name }}</span>

                                                                <i
                                                                        class="rango-arrow-right pr15 float-right"
                                                                        v-if="childSubCategory.children.length && childSubCategory.children.length > 0">
                                                                </i>

                                                            </a>
                                                            <div :class="`sub-categories sub-category-${sidebarLevel+childSubCategoryIndex} cursor-default sub-categories-4`">
                                                                <div
                                                                        class="sub-category-container"
                                                                        v-if="childSubCategory.children && childSubCategory.children.length > 0">

                                                                    <div
                                                                            @mouseout="toggleSidebar(id, $event, 'mouseout')"
                                                                            @mouseover="remainBar(`sidebar-level-${sidebarLevel+childSubCategoryIndex}-4`)"
                                                                            :class="`sub-category cursor-default`">

                                                                        <nav
                                                                                class="sidebar"
                                                                                :id="`sidebar-level-${sidebarLevel+childSubCategoryIndex}-4`"
                                                                                @mouseover="remainBar(`sidebar-level-${sidebarLevel+childSubCategoryIndex}-4`)">

                                                                            <ul type="none">
                                                                                <li
                                                                                        @mouseout="toggleSidebar(id, $event, 'mouseout')"
                                                                                        @mouseover="toggleSidebar(id, $event, 'mouseover')"
                                                                                        :id="`category-${subChildSubCategoryIndex}-${childSubCategoryIndex}-${subCategoryIndex}-${categoryIndex}`"
                                                                                        :key="`${subChildSubCategoryIndex}-${childSubCategoryIndex}-${subCategoryIndex}-${categoryIndex}`"
                                                                                        v-for="(subChildSubCategory, subChildSubCategoryIndex) in childSubCategory.children">

                                                                                    <a
                                                                                            :id="`sidebar-level-link-${subChildSubCategoryIndex}`"
                                                                                            @mouseout="toggleSidebar(id, $event, 'mouseout')"
                                                                                            :href="`${$root.baseUrl}/${category.slug}/${subCategory.slug}/${childSubCategory.slug}/${subChildSubCategory.slug}`"
                                                                                            :class="`category sub-category unset ${(childSubCategory.children.length > 0) ? 'fw6' : ''}`">

                                                                                        <div
                                                                                                class="category-icon"
                                                                                                @mouseout="toggleSidebar(id, $event, 'mouseout')"
                                                                                                @mouseover="toggleSidebar(id, $event, 'mouseover')">

                                                                                            <img
                                                                                                    v-if="subCategory.category_icon_path"
                                                                                                    :src="`${$root.baseUrl}/storage/${subChildSubCategory.category_icon_path}`" />
                                                                                        </div>
                                                                                        <span class="category-title">{{ subChildSubCategory.name }}</span>
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </nav>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </nav>
                                            </div>
                                        </div>
                                    </div>

                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </li>
        </ul>
    </nav>
</template>

<script type="text/javascript">
    export default {
        props: [
            'id',
            'addClass',
            'parentSlug',
            'mainSidebar',
            'categoryCount'
        ],

        data: function () {
            return {
                slicedCategories: [],
                // sidebarLevel: Math.floor(Math.random() * 1000),
                sidebarLevel: Math.floor(999),
            }
        },

        watch: {
            '$root.sharedRootCategories': function (categories) {
                console.log(categories[0]['children'][0]['children'])
                this.formatCategories(categories);
            }
        },

        methods: {
            toggleSidebar: function (id, {target}, type) {
                console.log('toggle sidebar')
                console.log('id ' + id)
                console.log({target})
                if (
                    Array.from(target.classList)[0] == "main-category"
                    || Array.from(target.parentElement.classList)[0] == "main-category"
                ) {
                    let sidebar = $(`#sidebar-level-${id}`);

                    if (sidebar && sidebar.length > 0) {
                        if (type == "mouseover") {
                            console.log('mouseover text')
                            this.show(sidebar);
                        } else if (type == "mouseout") {
                            console.log('mouselive text')
                            this.hide(sidebar);
                        }
                    }
                } else if (
                    Array.from(target.classList)[0]     == "category"
                    || Array.from(target.classList)[0]  == "category-icon"
                    || Array.from(target.classList)[0]  == "category-title"
                    || Array.from(target.classList)[0]  == "category-content"
                    || Array.from(target.classList)[0]  == "rango-arrow-right"
                ) {
                    let parentItem = target.closest('li');

                        if (target.id || parentItem.id.match('category-')) {
                        let subCategories = $(`#${target.id ? target.id : parentItem.id} .sub-categories`);
                        let subCategoryText = `#${target.id ? target.id : parentItem.id} .sub-categories`;
                        if (target.id.match('sidebar-level-link-2-')) {
                            subCategories = $(`#${parentItem.id} .sub-categories`);
                        }
                        if (target.id.match('sidebar-level-link-3-')) {
                            subCategories = $(`#${parentItem.id} .sub-categories`);
                        }
                        if (subCategories && subCategories.length > 0) {
                            // console.log(subCategoryText)
                            // console.log('not empty')
                            console.log('array from subcategory')
                            console.log(Array.from(subCategories))
                            let subCategories1 = Array.from(subCategories)[0];
                            let subCategories2 = $('div.sub-categories.cursor-default.sub-categories-2');
                            let subCategories3 = $('div.sub-categories.cursor-default.sub-categories-3');
                            subCategories1 = $(subCategories1);
                            console.log('subcategories1')
                            console.log(subCategories1)
                            console.log(type)

                            if (type == "mouseover") {
                                // console.log('show subcategory')
                                this.show(subCategories1);
                                // this.remainBar('sidebar-level-999-3')
                                // this.show(subCategories2);
                                // this.show(subCategories3);

                                let sidebarChild = subCategories1.find('.sidebar');
                                // let sidebarChild2 = subCategories2.find('.sidebar');
                                // let sidebarChild3 = subCategories3.find('.sidebar');
                                this.show(sidebarChild);
                                // this.show(sidebarChild2);
                                // this.show(sidebarChild3);
                            }
                            else if (type == "mouseout") {
                                this.hide(subCategories1);
                                // this.remainBar('sidebar-level-999-3')
                            }
                        } else {
                            console.log('empty')
                            let subCategoryText = `#${target.id ? target.id : parentItem.id} .sub-categories`;
                            // console.log(subCategoryText)
                            if (type == "mouseout") {
                                let sidebar = $(`#${id}`);
                                sidebar.hide();
                            }
                        }
                    }
                }
            },

            show: function (element) {
                element.show();
                element.mouseleave(({target}) => {
                    // $(target.closest('.sidebar')).hide();
                });
            },
            remainBar: function (id) {
                console.log('remain bar')
                console.log(id)
                let sidebar = $(`#${id}`);
                if (sidebar && sidebar.length > 0) {
                    sidebar.show();

                    let actualId = id.replace('sidebar-level-', '');

                    let sidebarContainer = sidebar.closest(`.sub-category-${actualId}`)
                    if (sidebarContainer && sidebarContainer.length > 0) {
                        sidebarContainer.show();
                    }

                }
            },

            formatCategories: function (categories) {
                let slicedCategories = categories;
                let categoryCount = this.categoryCount ? this.categoryCount : 9;

                if (
                    slicedCategories
                    && slicedCategories.length > categoryCount
                ) {
                    slicedCategories = categories.slice(0, categoryCount);
                }

                if (this.parentSlug)
                    slicedCategories['parentSlug'] = this.parentSlug;

                this.slicedCategories = slicedCategories;
            },
        }
    }
</script>