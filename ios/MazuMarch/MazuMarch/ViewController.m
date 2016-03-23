//
//  ViewController.m
//  MazuMarch
//
//  Created by OA Wu on 2016/3/23.
//  Copyright © 2016年 OA Wu. All rights reserved.
//

#import "ViewController.h"

@interface ViewController ()

@end

@implementation ViewController

- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.
    [self initUI];
    
    NSDateFormatter *dateFormatter = [NSDateFormatter new];
    [dateFormatter setDateFormat:@"yyyy-MM-dd hh:mm:ss"];
    
    [Path create:@{
       @"lat": [NSString stringWithFormat:@"%f", 25.179269],
       @"lng": [NSString stringWithFormat:@"%f", 121.452371],
       @"al": [NSString stringWithFormat:@"%f", 77.525513],
       @"ah": [NSString stringWithFormat:@"%f", 65.0],
       @"av": [NSString stringWithFormat:@"%f", 10.000000],
       @"sd": [NSString stringWithFormat:@"%f", -1.000000],
       @"ct": [dateFormatter stringFromDate:[NSDate date]]
       }];
    
    
    
    
    
    
    NSMutableDictionary *parameters = [NSMutableDictionary new];
    int i = 0;
    for (Path* path in paths)
        [parameters setValue:[path toDictionary] forKey:[NSString stringWithFormat:@"%d", i++]];
    
    NSMutableDictionary *data = [NSMutableDictionary new];
    [data setValue:parameters forKey:@"paths"];
    if (DEV) NSLog(@"=======>url:%@", [NSString stringWithFormat:API_POST_POLYLINES_PAYHS, (int)[self.polylineId integerValue]]);
    
    AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
    [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
    [httpManager POST:[NSString stringWithFormat:API_POST_POLYLINES_PAYHS, (int)[self.polylineId integerValue]]
           parameters:data
              success:^(AFHTTPRequestOperation *operation, id responseObject) {
                  self.isUploadPaths = NO;
                  
                  if ((int)[(NSArray *)[responseObject objectForKey:@"ids"] count] > 0)
                      [Path deleteAll:[NSString stringWithFormat:@"id IN (%@)", [[responseObject objectForKey:@"ids"] componentsJoinedByString:@", "]]];
                  
                  [((GPSViewController *)self.gpsControler) rotateSpinningView];
                  
                  if (finish) finish();
                  else [((GPSViewController *)self.gpsControler) setMap:[responseObject objectForKey:@"paths"]];
              }
              failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                  self.isUploadPaths = NO;
                  if (DEV) NSLog(@"=======>Failure!Error:%@", [[NSString alloc] initWithData:(NSData *)error.userInfo[AFNetworkingOperationFailingURLResponseDataErrorKey] encoding:NSUTF8StringEncoding]);
                  
                  if (finish) finish();
              }
     ];
}

- (void)initUI {
    self.locationManager = [CLLocationManager new];
    [self.locationManager setDelegate:self];
    [self.locationManager setDistanceFilter:0];
    [self.locationManager setDesiredAccuracy:kCLLocationAccuracyBest];
    [self.locationManager setAllowsBackgroundLocationUpdates:YES];
    [self.locationManager requestAlwaysAuthorization];
    
    self.switchButton = [UISwitch new];
    [self.switchButton setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.switchButton addTarget:self action:@selector(setState:) forControlEvents:UIControlEventValueChanged];

    
    [self.view addSubview:self.switchButton];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.switchButton attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.topLayoutGuide attribute:NSLayoutAttributeBottom multiplier:1 constant:20.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.switchButton attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeading multiplier:1 constant:10.0]];
    
    
    self.switchLabel = [UILabel new];
    [self.switchLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.switchLabel setText:@"關閉"];

    
    [self.view addSubview:self.switchLabel];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.switchLabel attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.switchButton attribute:NSLayoutAttributeRight multiplier:1 constant:10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.switchLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.switchButton attribute:NSLayoutAttributeCenterY multiplier:1 constant:0.0]];
    
    
    self.logTextView = [UITextView new];
    [self.logTextView setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.logTextView setEditable:NO];
    
    [self.logTextView.layer setBorderColor:[UIColor colorWithRed:0 green:0 blue:0 alpha:.4].CGColor];
    [self.logTextView.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
    [self.logTextView.layer setCornerRadius:2];
    [self.logTextView setClipsToBounds:YES];
    
    [self.view addSubview:self.logTextView];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.logTextView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.switchButton attribute:NSLayoutAttributeBottom multiplier:1 constant:20.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.logTextView attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeading multiplier:1 constant:10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.logTextView attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTrailing multiplier:1 constant:-10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.logTextView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.bottomLayoutGuide attribute:NSLayoutAttributeTop multiplier:1 constant:-10.0]];
}
-(void)setState:(id)sender {
    BOOL state = [sender isOn];
    if (state) {
        [self.locationManager startUpdatingLocation];
        [self.switchLabel setText:@"開啟中.."];
        [self log:@"開啟中.."];
        [self log:@"==================================="];
        [self.switchLabel setText:@"開啟"];
        [self log:@"已開啟"];
    } else {
        [self log:@"==================================="];
        [self.switchLabel setText:@"關閉中.."];
        [self log:@"關閉中.."];
        [self log:@"----------------------------------------"];
        [self log:@"已關閉"];
        [self.switchLabel setText:@"關閉"];

        
        [self.locationManager stopUpdatingLocation];
        [self log:@""];
    }
}
-(void)log:(NSString *)log {
    [self.logTextView insertText:[NSString stringWithFormat:@"  %@\n", log]];
    [self.logTextView scrollRangeToVisible:NSMakeRange(self.logTextView.text.length - 1, 1)];
    
}

-(void)locationManager:(CLLocationManager *)manager didUpdateLocations:(NSArray<CLLocation *> *)locations {
     NSDateFormatter *dateFormatter = [NSDateFormatter new];
    [dateFormatter setDateFormat:@"yyyy-MM-dd hh:mm:ss"];
    
    CLLocation *location = [locations firstObject];
    double lat = location.coordinate.latitude;
    double lng = location.coordinate.longitude;
    double speed = location.speed;
    double ha = location.horizontalAccuracy;
    double va = location.verticalAccuracy;

    [self log:@"----------------------------------------"];
    [self log:[NSString stringWithFormat:@"經度：%.5f",lat]];
    [self log:[NSString stringWithFormat:@"緯度：%.5f",lng]];
    [self log:[NSString stringWithFormat:@"速度：%.3f Km/H",speed]];
    [self log:[NSString stringWithFormat:@"水平準度：%.1f 公尺",ha]];
    [self log:[NSString stringWithFormat:@"海拔準度：%.1f 公尺",va]];
    [self log:[NSString stringWithFormat:@"目前時間：%@", [dateFormatter stringFromDate:[NSDate date]]]];

    
    
}
- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
