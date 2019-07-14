import React from "react";

const withSession = Component => props => (
  <div>
    <Component {...props} />;
  </div>
);

export default withSession;
