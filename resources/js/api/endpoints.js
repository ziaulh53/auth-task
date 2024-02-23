// client endpoints
export const auth = {
    login: '/signin',
    reg: '/signup',
    logout: '/signout',
    forgetPassword: '/forgot-password',
    resetPassword: '/reset-password',
    editProfile: '/update-profile',
    changePassword: '/change-password',
    changeEmailRequest: '/change-email-request',
    changeEmail: '/change-email'
}

export const categoryEndpoint = {
    fetchCategory: '/category/',
    fetchSingleCategory: '/category/',
}
export const productEndpoint = {
    fetchSingleProduct: '/product/',
    fetchSuggestionProducts: '/product-suggestion',
    fetchNewArrival: '/product/new-arrival',
    fetchProductOnBrand: '/brands/',
}

export const landingEndpoint = {
    fetchHomepage: '/landing',
    getAllbrands: "/brands"
}

export const orderEndpoint = {
    getOrders: '/order/',
    createOrder: '/order/create',
    getSingleOrder: '/order/',
    updateStatus: '/order/update-status/'
}

export const wishEndpoint = {
    getWishItems: '/wish',
    addWish: '/wish/create',
}

export const addressEndpoint = {
    getAddress: '/address/',
}



// admin endpoints

export const authAdmin = {
    login: '/admin/login',
    reg: '/admin/register',
    forgetPassword: '/admin/forget-password',
    resetPassword: '/admin/reset-password'
}

export const user = {
    getUser: '/admin/user'
}

export const brandAdmin = {
    getBrands: '/admin/brand/',
    createBrand: '/admin/brand/create',
    editBrand: '/admin/brand/edit/',
    deleteBrand: '/admin/brand/delete/',
}
export const categoryAdmin = {
    getCategory: '/admin/category/',
    createCategory: '/admin/category/create',
    editCategory: '/admin/category/edit/',
    deleteCategory: '/admin/category/delete/',
}

export const productAdmin = {
    getProdcuts: '/admin/product/',
    createProduct: '/admin/product/create',
    editProduct: '/admin/product/edit/',
    deleteProduct: '/admin/product/delete/',
}

export const colorAdmin = {
    getColors: '/admin/color/',
    createColor: '/admin/color/create',
    editColor: '/admin/color/edit/',
    deleteColor: '/admin/color/delete/',
}

export const landingAdmin = {
    getHomePageData: '/admin/landing/',
    updateBanner: '/admin/landing/banner',
    updateTrending: '/admin/landing/trending',
}
export const orderEndpointAdmin = {
    getOrders: '/admin/order/',
    updateStatus: '/admin/order/update-status/',
}

export const dashboardEndpointAdmin = {
    getDashboardData: '/admin/dashboard/',
    getCategorySelling: '/admin/dashboard/by-category/'
}
