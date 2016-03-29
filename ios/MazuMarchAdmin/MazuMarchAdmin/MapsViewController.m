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
    
    self.path = [GMSPolyline new];
    [self.path setStrokeColor:[UIColor colorWithRed:249/255.0f
                                              green:39/255.0f
                                               blue:114/255.0f
                                              alpha:.5f]];
    [self.path setStrokeWidth:3.0f];
    
    
    self.mazu = [GMSMarker new];
    [self.mazu setIcon:[[UIImage imageNamed:@"mazu"] imageWithRenderingMode:UIImageRenderingModeAlwaysOriginal]];
    [self.mazu setMap:self.mapView];
    [self.mazu setPosition:[MapsViewController oriLocation]];
}
- (void)viewWillAppear:(BOOL)animated {
    [super viewWillAppear:animated];
    [self reloadData];
}
- (void)viewDidDisappear:(BOOL)animated {
    [super viewDidDisappear:animated];
    [self clean];
}
-(void)clean {
    
    self.isLoading = YES;
    self.paths = [NSMutableArray new];
    [self.path setMap:nil];
    [self.mazu setPosition:[MapsViewController oriLocation]];

    if (self.timer) {
        [self.timer invalidate];
        self.timer = nil;
    }
}
- (void)reloadData {
    UIAlertController *alert = [UIAlertController alertControllerWithTitle:@"取得資料中" message:@"請稍候..." preferredStyle:UIAlertControllerStyleAlert];
    
    [self.parentViewController presentViewController:alert animated:YES completion:^{

        self.isLoading = NO;
        self.paths = [NSMutableArray new];
        [self loadData:alert];
        self.timer = [NSTimer scheduledTimerWithTimeInterval:LOAD_PATH_TIMER target:self selector:@selector(loadDataByTimer) userInfo:nil repeats:YES];
    }];
}
- (void)reloadMap {
    GMSMutablePath *path = [GMSMutablePath path];
    for (Path *p in self.paths)
        [path addCoordinate:p.position];
    
    if (((int)[self.paths count]) < 1)
        return;

    [self.path setPath:path];
    [self.path setMap:self.mapView];
    
    [self.mazu setPosition:[self.paths lastObject].position];
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
    self.paths = [NSMutableArray new];
    [self.path setMap:nil];
    
    AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
    [httpManager.requestSerializer setCachePolicy:NSURLRequestReloadIgnoringLocalCacheData];
    [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"text/plain"]];
    [httpManager GET:[NSString stringWithFormat:LOAD_PATHS_API_URL, (int)[[USER_DEFAULTS objectForKey:@"march_id"] integerValue]]
          parameters:nil
             success:^(AFHTTPRequestOperation *operation, id responseObject) {
                 self.isLoading = NO;

                 if (![[responseObject objectForKey:@"s"] boolValue]) {
                     if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
                     return;
                 }

                 for (NSDictionary *p in [responseObject objectForKey:@"p"])
                     [self.paths addObject: [[Path alloc] initWithDictionary: p]];
                 
                 [self reloadMap];


                 if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
             }
             failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                 self.isLoading = NO;
                 if (alert) [alert dismissViewControllerAnimated:YES completion:nil];
             }
     ];
}


- (void)turnTraffic:(UIBarButtonItem *)sender {
    if (sender.tag == 1) {
        [sender setTitle:self.mapView.trafficEnabled ? @"開啟路況" : @"關閉路況"];
        [self.mapView setTrafficEnabled:!self.mapView.trafficEnabled];
    } else {
        [self.mapView setCamera:[GMSCameraPosition cameraWithLatitude:self.mazu.position.latitude
                                                            longitude:self.mazu.position.longitude
                                                                 zoom:15]];
    }
}
-(void)initNavigationItem {
    
    UIBarButtonItem *rightButton = [[UIBarButtonItem alloc] initWithTitle:@"開啟路況" style:UIBarButtonItemStylePlain target:self action:@selector(turnTraffic:)];
    [rightButton setTintColor:[UIColor colorWithRed:1.00 green:1.00 blue:1.00 alpha:1.00]];
    [rightButton setTag:1];
    [self.navigationItem setRightBarButtonItem:rightButton];
    
    UIBarButtonItem *leftButton = [[UIBarButtonItem alloc] initWithTitle:@"媽祖位置" style:UIBarButtonItemStylePlain target:self action:@selector(turnTraffic:)];
    [leftButton setTintColor:[UIColor colorWithRed:1.00 green:1.00 blue:1.00 alpha:1.00]];
    [leftButton setTag:2];
    [self.navigationItem setLeftBarButtonItem:leftButton];

}
-(void)initLocationManager {
    
    self.locationManager = [CLLocationManager new];
    [self.locationManager setDesiredAccuracy:kCLLocationAccuracyBest];
    [self.locationManager requestAlwaysAuthorization];
    

//    [self.mapView setCamera:[GMSCameraPosition cameraWithLatitude:23.5695962314
//                                                        longitude:120.30357033
//                                                             zoom:15]];
//    [self.mapView setCenter:CGPointMake(23.5695962314, 120.30357033)];
//    [self.mazu setPosition:CLLocationCoordinate2DMake(23.5694962314, 120.30317033)];
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
