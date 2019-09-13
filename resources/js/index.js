import { BrowserRouter, Switch, Redirect, Route } from "react-router-dom";
import React, { Fragment } from "react";
import ReactDOM from "react-dom";

import logger from "redux-logger";
import { applyMiddleware, createStore, combineReducers } from "redux";
import thunk from "redux-thunk";
import promise from "redux-promise-middleware";
import { Provider } from "react-redux";
import reducers from "./redux/reducers/index";

import withSession from "./components/withSession";

import Navbar from "./components/Navbar.js";
import App from "./components/App.js";
import ProductPage from "./components/Products/ProductPage";
import Page404 from "./components/Page404";
import Login from "./components/Users/Login";
import Logout from "./components/Users/Logout";
import Footer from "./components/Footer";

const middleware = applyMiddleware(/*promise,*/ thunk, logger);
const store = createStore(reducers, middleware);

const Root = () => (
    <BrowserRouter>
        <Fragment>
            <Navbar />
            <Switch>
                <Route path="/" exact component={withSession(App)} />
                <Route
                    path="/products/:id"
                    exact
                    component={withSession(ProductPage)}
                />
                <Route path="/login" exact component={withSession(Login)} />
                <Route path="/logout" exact component={withSession(Logout)} />
                <Route path="/404" exact component={Page404} />
                <Redirect to="/404" />
            </Switch>
            <Footer />
        </Fragment>
    </BrowserRouter>
);

// const RootWithSession = withSession(Root);

ReactDOM.render(
    <Provider store={store}>
        <Root />
    </Provider>,
    document.getElementById("root")
);

// If you want your app to work offline and load faster, you can change
// unregister() to register() below. Note this comes with some pitfalls.
// Learn more about service workers: https://bit.ly/CRA-PWA
