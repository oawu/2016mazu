//
//  MapsViewController.m
//  MazuMarchAdmin
//
//  Created by OA Wu on 2016/3/28.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "MapsViewController.h"


@interface MapsViewController ()

@end

@implementation MapsViewController


+ (CLLocationCoordinate2D) oriLocation {
    return CLLocationCoordinate2DMake(23.567600533837, 120.30456438661);
}
- (void)viewDidLoad {
    [super viewDidLoad];
    [self initLocationManager];
    [self initMapView];
    [self initNavigationItem];
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    [self clean];
    [self reloadData];
    [self loadPolyline];
}
- (void)viewDidDisappear:(BOOL)animated {
    [super viewDidDisappear:animated];
    [self clean];

    [self.polyline setPath:[GMSMutablePath path]];
    [self.polyline setMap:nil];
}
-(void)clean {
    
    self.isLoading = YES;
    
    for (Marker *marker in self.markers)
        [marker cleanAll];
    
    self.markers = [NSMutableArray new];

    if (self.timer) {
        [self.timer invalidate];
        self.timer = nil;
    }
}
- (void)reloadData {
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"取得資料中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{

        self.isLoading = NO;
        self.markers = [NSMutableArray new];
        
        [self loadData:alert];
        self.timer = [NSTimer scheduledTimerWithTimeInterval:LOAD_PATH_TIMER target:self selector:@selector(loadDataByTimer) userInfo:nil repeats:YES];
    }];
}
- (void)loadDataByTimer {
    [self loadData: nil];
}
- (void)loadData:(UIAlertController *)alert {
    if (self.isLoading) {
        if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
        return;
    }
    
    self.isLoading = YES;
    
    AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
    [httpManager.requestSerializer setCachePolicy:NSURLRequestReloadIgnoringLocalCacheData];
    [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
    [httpManager GET:LOAD_GPS_API_URL
          parameters:nil
             success:^(AFHTTPRequestOperation *operation, id responseObject) {
                 self.isLoading = NO;
                 
                 if ((int)[[responseObject objectForKey:@"m"] count] > 0) {
                     
                     for (Marker *marker in self.markers)
                         [marker cleanAll];

                     for (NSDictionary *m in [responseObject objectForKey:@"m"])
                         [self.markers addObject:[[Marker alloc] initWithDictionary:m map:self.mapView]];
                 }
                  if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
             }
             failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                 self.isLoading = NO;
                 if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
             }
     ];
}
- (void)loadPolyline {
    self.polyline = [GMSPolyline new];
    [self.polyline setStrokeColor:[UIColor colorWithRed:101/255.0f green:216/255.0f blue:238/255.0f alpha:.4f]];
    [self.polyline setStrokeWidth:4.0f];
    
    AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
    [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
    [httpManager GET:GET_SETTING_API_URL
          parameters:nil
             success:^(AFHTTPRequestOperation *operation, id responseObject) {
                 if ([[NSString stringWithFormat:@"%@", [responseObject objectForKey:@"path_id"]] isEqualToString:@"0"])
                     return ;
                 AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
                 [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
                 [httpManager GET:[NSString stringWithFormat:@"%@%@.json", LOAD_PATH_API_URL, [responseObject objectForKey:@"path_id"]]
                       parameters:nil
                          success:^(AFHTTPRequestOperation *operation, id responseObject) {

                              GMSMutablePath *path = [GMSMutablePath path];
                              for (NSDictionary *p in responseObject)
                                  [path addCoordinate:CLLocationCoordinate2DMake([[p objectForKey:@"a"] doubleValue], [[p objectForKey:@"n"] doubleValue])];

                              [self.polyline setPath:path];
                              [self.polyline setMap:self.mapView];

                          }
                          failure:nil
                  ];
             }
             failure:nil
     ];
}

- (void)turnTraffic:(UIBarButtonItem *)sender {
    if (sender.tag == 1) {
        [sender setTitle:self.mapView.trafficEnabled ? @"開啟路況" : @"關閉路況"];
        [self.mapView setTrafficEnabled:!self.mapView.trafficEnabled];
    } else {
        CLLocationCoordinate2D myLocation = [self.markers firstObject].marker.position;
        GMSCoordinateBounds *bounds = [[GMSCoordinateBounds alloc] initWithCoordinate:myLocation coordinate:myLocation];
        
        for (Marker *marker in self.markers)
            bounds = [bounds includingCoordinate:marker.marker.position];
        
        [self.mapView animateWithCameraUpdate:[GMSCameraUpdate fitBounds:bounds withPadding:50.0f]];
    }
}
-(void)initNavigationItem {
    
    UIBarButtonItem *rightButton = [[UIBarButtonItem alloc] initWithTitle:@"開啟路況" style:UIBarButtonItemStylePlain target:self action:@selector(turnTraffic:)];
    [rightButton setTintColor:[UIColor colorWithRed:1.00 green:1.00 blue:1.00 alpha:1.00]];
    [rightButton setTag:1];
    [self.navigationItem setRightBarButtonItem:rightButton];
    
    UIBarButtonItem *leftButton = [[UIBarButtonItem alloc] initWithTitle:@"陣頭位置" style:UIBarButtonItemStylePlain target:self action:@selector(turnTraffic:)];
    [leftButton setTintColor:[UIColor colorWithRed:1.00 green:1.00 blue:1.00 alpha:1.00]];
    [leftButton setTag:2];
    [self.navigationItem setLeftBarButtonItem:leftButton];

}
-(void)initLocationManager {
    
    self.locationManager = [CLLocationManager new];
    [self.locationManager setDesiredAccuracy:kCLLocationAccuracyBest];
    [self.locationManager requestAlwaysAuthorization];
}

-(void)initMapView {
    self.mapView = [GMSMapView mapWithFrame:CGRectZero camera:[GMSCameraPosition cameraWithLatitude:[MapsViewController oriLocation].latitude
                                                                                          longitude:[MapsViewController oriLocation].longitude
                                                                                               zoom:15]];
    [self.mapView setAccessibilityElementsHidden:NO];
    [self.mapView setMyLocationEnabled:YES];
    [self.mapView.settings setMyLocationButton:YES];
    [self.mapView setPadding:UIEdgeInsetsMake(0.0, 0.0, 50.0, 0.0)];
    
    [self.mapView setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.view addSubview:self.mapView];
    
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTop multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeBottom multiplier:1 constant:0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeading multiplier:1 constant:0.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.mapView attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTrailing multiplier:1 constant:0.0]];
    
    

}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

/*
#pragma mark - Navigation

// In a storyboard-based application, you will often want to do a little preparation before navigation
- (void)prepareForSegue:(UIStoryboardSegue *)segue sender:(id)sender {
    // Get the new view controller using [segue destinationViewController].
    // Pass the selected object to the new view controller.
}
*/

@end
