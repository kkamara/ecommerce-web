import React, { Component } from "react";

import { currentUserActions } from "../redux/actions/index";
import { connect } from "react-redux";
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
                storeNewCacheHashToken();
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
        current_user: state.current_user
    });
    const mapDispatchToProps = dispatch => ({
        getCurrentUser: () => dispatch(currentUserActions.getCurrentUser())
    });

    return connect(
        mapStateToProps,
        mapDispatchToProps
    )(withSession);
}
