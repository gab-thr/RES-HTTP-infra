<?php
$STATIC_APP_0 = getenv('STATIC_APP_0');
$STATIC_APP_1 = getenv('STATIC_APP_1');
$DYNAMIC_APP_0 = getenv('DYNAMIC_APP_0');
$DYNAMIC_APP_1 = getenv('DYNAMIC_APP_1');
?>

<VirtualHost *:80>
    ServerName demo.res.ch

    # enable sticky sessions
    Header add Set-Cookie 'ROUTEID=.%{BALANCER_WORKER_ROUTE}e; path=/' env=BALANCER_ROUTE_CHANGED

    # balance config for lorem app
    <Proxy "balancer://dynamic_app">
        BalancerMember 'http://<?php print "$DYNAMIC_APP_0" ?>'
        BalancerMember 'http://<?php print "$DYNAMIC_APP_1" ?>'
    </Proxy>


    ProxyPass '/api/lorem/' 'balancer://dynamic_app/'
    ProxyPassReverse '/api/lorem/' 'balancer://dynamic_app/'


    # balance config for static site
    <Proxy "balancer://static_app">
        BalancerMember 'http://<?php print "$STATIC_APP_0" ?>' route=1
        BalancerMember 'http://<?php print "$STATIC_APP_1" ?>' route=2
        ProxySet stickysession=ROUTEID
    </Proxy>

    ProxyPass '/' 'balancer://static_app/'
    ProxyPassReverse '/' 'balancer://static_app/'


    # config for load balance manager
    <Location '/balancer-manager'>
        SetHandler balancer-manager
    </Location>

    ProxyPass '/balancer-manager' !

</VirtualHost>