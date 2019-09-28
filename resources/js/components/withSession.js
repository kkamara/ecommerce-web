import React, { Component } from "react";

import { userActions } from "../redux/actions/index";
import { connect } from "react-redux";
import { CACHE_HASH_NAME } from "../constants";
import { getCacheHashToken } from "../utilities/methods";

export default function(ComposedComponent) {
    class withSession extends Component {
        componentDidMount() {
            this.loadCurrentUser();

            this.handlingCacheKey();
        }

        componentDidUpdate() {
            this.handlingCacheKey();
        }

        loadCurrentUser() {
            this.props.getCurrentUser();
        }

        handlingCacheKey = () => {
            const { current_user } = this.props;
            // set unique cookie for backend cache cart
            if (
                null === getCacheHashToken() &&
                (current_user === undefined || !current_user.user)
            )
                localStorage.setItem(
                    CACHE_HASH_NAME,
                    Math.floor(new Date().getTime() * Math.random(0, 1000000))
                );
        };

        render() {
            const { current_user } = this.props;

            const userAuthenticated =
                current_user.isLoaded && current_user.fetched;

            return (
                <ComposedComponent
                    {...this.props}
                    current_user={current_user}
                    userAuthenticated={userAuthenticated}
                />
            );
        }
    }

    const mapStateToProps = state => ({
        current_user: state.user.user
    });
    const mapDispatchToProps = dispatch => ({
        getCurrentUser: () => dispatch(userActions.getCurrentUser())
    });

    return connect(
        mapStateToProps,
        mapDispatchToProps
    )(withSession);
}
