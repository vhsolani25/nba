function initialState() {
    return {
        item: {
            id: null,
            name: null,
            image: null,
            order: null,
            status: null
        },

        loading: false
    };
}

const getters = {
    item: state => state.item,
    loading: state => state.loading
};

const actions = {
    storeImageData({ commit, state, dispatch }, params) {
        commit("setLoading", true);
        dispatch("Alert/resetState", null, { root: true });

        return new Promise((resolve, reject) => {
            let settings = {
                headers: { "content-type": "multipart/form-data" }
            };

            axios
                .post("/api/v1/images", params)
                .then(response => {
                    commit("resetState");
                    resolve();
                })
                .catch(error => {
                    let message = error.response.data.message || error.message;
                    let errors = error.response.data.errors;

                    dispatch(
                        "Alert/setAlert",
                        { message: message, errors: errors, color: "danger" },
                        { root: true }
                    );

                    reject(error);
                })
                .finally(() => {
                    commit("setLoading", false);
                });
        });
    },
    storeData({ commit, state, dispatch }) {
        commit("setLoading", true);
        dispatch("Alert/resetState", null, { root: true });

        return new Promise((resolve, reject) => {
            let params = new FormData();
            let settings = {
                headers: { "content-type": "multipart/form-data" }
            };

            for (let fieldName in state.item) {
                let fieldValue = state.item[fieldName];
                if (typeof fieldValue !== "object") {
                    params.set(fieldName, fieldValue);
                } else {
                    if (fieldValue && typeof fieldValue[0] !== "object") {
                        params.set(fieldName, fieldValue);
                    } else {
                        for (let index in fieldValue) {
                            params.set(
                                fieldName + "[" + index + "]",
                                fieldValue[index]
                            );
                        }
                    }
                }
            }
            if (state.item.image === null) {
                params.delete("image");
            }

            axios
                .post("/api/v1/images", params)
                .then(response => {
                    commit("resetState");
                    resolve();
                })
                .catch(error => {
                    let message = error.response.data.message || error.message;
                    let errors = error.response.data.errors;

                    dispatch(
                        "Alert/setAlert",
                        { message: message, errors: errors, color: "danger" },
                        { root: true }
                    );

                    reject(error);
                })
                .finally(() => {
                    commit("setLoading", false);
                });
        });
    },
    updateData({ commit, state, dispatch }) {
        commit("setLoading", true);
        dispatch("Alert/resetState", null, { root: true });

        return new Promise((resolve, reject) => {
            let params = new FormData();
            params.set("_method", "PUT");

            for (let fieldName in state.item) {
                let fieldValue = state.item[fieldName];
                if (typeof fieldValue !== "object") {
                    params.set(fieldName, fieldValue);
                } else {
                    if (fieldValue && typeof fieldValue[0] !== "object") {
                        params.set(fieldName, fieldValue);
                    } else {
                        for (let index in fieldValue) {
                            params.set(
                                fieldName + "[" + index + "]",
                                fieldValue[index]
                            );
                        }
                    }
                }
            }

            if (state.item.image === null) {
                params.delete("image");
            }

            axios
                .post("/api/v1/images/" + state.item.id, params)
                .then(response => {
                    commit("setItem", response.data.data);
                    resolve();
                })
                .catch(error => {
                    let message = error.response.data.message || error.message;
                    let errors = error.response.data.errors;

                    dispatch(
                        "Alert/setAlert",
                        { message: message, errors: errors, color: "danger" },
                        { root: true }
                    );

                    reject(error);
                })
                .finally(() => {
                    commit("setLoading", false);
                });
        });
    },
    fetchData({ commit, dispatch }, id) {
        axios.get("/api/v1/images/" + id).then(response => {
            commit("setItem", response.data.data);
        });
    },

    setName({ commit }, value) {
        commit("setName", value);
    },
    setImage({ commit }, value) {
        commit("setImage", value);
    },

    setOrder({ commit }, value) {
        commit("setOrder", value);
    },
    setStatus({ commit }, value) {
        commit("setStatus", value);
    },
    resetState({ commit }) {
        commit("resetState");
    }
};

const mutations = {
    setItem(state, item) {
        state.item = item;
    },
    setName(state, value) {
        state.item.name = value;
    },
    setImage(state, value) {
        console.log(value);
        state.item.image = value;
    },
    setOrder(state, value) {
        state.item.order = value;
    },
    setStatus(state, value) {
        state.item.status = value;
    },

    setLoading(state, loading) {
        state.loading = loading;
    },
    resetState(state) {
        state = Object.assign(state, initialState());
    }
};

export default {
    namespaced: true,
    state: initialState,
    getters,
    actions,
    mutations
};
