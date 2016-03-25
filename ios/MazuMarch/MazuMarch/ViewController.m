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
    
}

- (void)initUI {
    self.marchId = 1;

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
    
    
    self.segmentedControl = [[UISegmentedControl alloc] initWithItems:@[@"十九早", @"十九中", @"十九晚", @"二十早", @"二十中", @"二十晚"]];
    [self.segmentedControl setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.segmentedControl addTarget:self action:@selector(chooseOne:) forControlEvents:UIControlEventValueChanged];
    [self.segmentedControl setSelectedSegmentIndex:self.marchId - 1];
    
    [self.view addSubview:self.segmentedControl];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.segmentedControl attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.switchButton attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.segmentedControl attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.switchLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:15.0]];
    
    
    self.uploadLogTextView = [UITextView new];
    [self.uploadLogTextView setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.uploadLogTextView setEditable:NO];
    
    [self.uploadLogTextView.layer setBorderColor:[UIColor colorWithRed:0 green:0 blue:0 alpha:.4].CGColor];
    [self.uploadLogTextView.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
    [self.uploadLogTextView.layer setCornerRadius:2];
    [self.uploadLogTextView setClipsToBounds:YES];
    
    [self.view addSubview:self.uploadLogTextView];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.uploadLogTextView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.bottomLayoutGuide attribute:NSLayoutAttributeTop multiplier:1 constant:-10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.uploadLogTextView attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeading multiplier:1 constant:10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.uploadLogTextView attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTrailing multiplier:1 constant:-10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.uploadLogTextView attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:200.0]];
    
    self.locationLogTextView = [UITextView new];
    [self.locationLogTextView setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.locationLogTextView setEditable:NO];
    
    [self.locationLogTextView.layer setBorderColor:[UIColor colorWithRed:0 green:0 blue:0 alpha:.4].CGColor];
    [self.locationLogTextView.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
    [self.locationLogTextView.layer setCornerRadius:2];
    [self.locationLogTextView setClipsToBounds:YES];
    
    [self.view addSubview:self.locationLogTextView];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.locationLogTextView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.segmentedControl attribute:NSLayoutAttributeBottom multiplier:1 constant:15.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.locationLogTextView attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeading multiplier:1 constant:10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.locationLogTextView attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTrailing multiplier:1 constant:-10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.locationLogTextView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.uploadLogTextView attribute:NSLayoutAttributeTop multiplier:1 constant:-10.0]];
}
- (void)chooseOne:(id)sender {
    self.marchId = (int)[sender selectedSegmentIndex] + 1;
}

-(void)setState:(id)sender {
    BOOL state = [sender isOn];
    if (state) {
        [self cleanLocationLog];
        [self cleanUploadLog];
        
        [self.switchLabel setText:@"開啟中.."];
        
        [self locationLog:@"開啟中.."];
        [self locationLog:@"==================================="];

        [self uploadLog:@"開啟中.."];
        [self uploadLog:@"==================================="];

        [self.locationManager startUpdatingLocation];
        [self.locationManager startMonitoringSignificantLocationChanges];
        [self locationLog:@"開啟定位系統"];
        [self locationLog:@"----------------------------------------"];
        
        
        [self.segmentedControl setEnabled:NO];
        [self locationLog:@"鎖定選擇器"];
        [self locationLog:@"----------------------------------------"];
        
        
        self.isUpload = NO;
        self.timer = [NSTimer scheduledTimerWithTimeInterval:PATH_FETCH_TIMER target:self selector:@selector(uploadPaths) userInfo:nil repeats:YES];
        [self uploadLog:@"開啟計時器"];
        [self uploadLog:@"----------------------------------------"];
        
//        segmentedControl
        [self.switchLabel setText:@"開啟"];
        [self locationLog:@"已開啟"];
        [self uploadLog:@"已開啟"];
    } else {
        [self locationLog:@"==================================="];
        [self locationLog:@"關閉中.."];
        [self uploadLog:@"==================================="];
        [self uploadLog:@"關閉中.."];
        [self.switchLabel setText:@"關閉中.."];

        [self locationLog:@"----------------------------------------"];
        [self.locationManager stopUpdatingLocation];
        [self.locationManager stopMonitoringSignificantLocationChanges];
        [self locationLog:@"關閉定位系統"];
        
        [self locationLog:@"----------------------------------------"];
        [self.timer invalidate];
        self.timer = nil;
        [self uploadLog:@"關閉計時器"];
        
        [self.segmentedControl setEnabled:YES];
        [self locationLog:@"開啟選擇器"];
        [self locationLog:@"----------------------------------------"];
        
        
        [self uploadPaths];
        
        [self locationLog:@"----------------------------------------"];
        [self locationLog:@"已關閉"];
        [self uploadLog:@"----------------------------------------"];
        [self uploadLog:@"已關閉"];
        [self.switchLabel setText:@"關閉"];
    }
}

- (void)uploadPaths {
    if (self.isUpload) return;
    self.isUpload = YES;
    
    [self uploadLog:@"----------------------------------------"];
    [self uploadLog:@"上傳路徑"];

    NSArray *paths = [Path findAll: @{@"order": @"id DESC", @"limit": @UPLOAD_PATHS_LIMIT}];

    if (((int)[paths count]) < 1) {
//        self.isUpload = NO;
        [self uploadLog:@"----------------------------------------"];
        [self uploadLog:@"沒有節點！"];
//        return;
    }
    
    NSMutableDictionary *parameters = [NSMutableDictionary new];

    int i = 0;
    for (Path* path in paths) [parameters setValue:[path toDictionary] forKey:[NSString stringWithFormat:@"%d", i++]];
    
    NSMutableDictionary *data = [NSMutableDictionary new];
    [data setValue:parameters forKey:@"p"];
    
    AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
    [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
    [httpManager POST:[NSString stringWithFormat:API_POST_MARCH_PAYHS, self.marchId]
           parameters:data
              success:^(AFHTTPRequestOperation *operation, id responseObject) {
                  
                  self.isUpload = NO;

                  [self uploadLog:@"----------------------------------------"];
                  [self uploadLog:@"上傳成功"];
                  
                  if ((int)[(NSArray *)[responseObject objectForKey:@"ids"] count] > 0)
                      [Path deleteAll:[NSString stringWithFormat:@"id IN (%@)", [[responseObject objectForKey:@"ids"] componentsJoinedByString:@", "]]];
                  
                  [self uploadLog:@"----------------------------------------"];
                  [self uploadLog:@"清除舊資料"];
              }
              failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                  self.isUpload = NO;
                  [self uploadLog:@"----------------------------------------"];
                  [self uploadLog:@"上傳失敗"];
              }
     ];
}
-(void)locationLog:(NSString *)log {
    [self.locationLogTextView insertText:[NSString stringWithFormat:@"  %@\n", log]];
    [self.locationLogTextView scrollRangeToVisible:NSMakeRange(self.locationLogTextView.text.length - 1, 1)];
}
-(void)cleanLocationLog {
    [self.locationLogTextView setText:@""];
}
-(void)uploadLog:(NSString *)log {
    [self.uploadLogTextView insertText:[NSString stringWithFormat:@"  %@\n", log]];
    [self.uploadLogTextView scrollRangeToVisible:NSMakeRange(self.uploadLogTextView.text.length - 1, 1)];
}
-(void)cleanUploadLog {
    [self.uploadLogTextView setText:@""];
}

-(void)locationManager:(CLLocationManager *)manager didUpdateLocations:(NSArray<CLLocation *> *)locations {
     NSDateFormatter *dateFormatter = [NSDateFormatter new];
    [dateFormatter setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    NSString *t = [dateFormatter stringFromDate:[NSDate date]];
    
    CLLocation *location = [locations firstObject];
    double a = location.coordinate.latitude;
    double n = location.coordinate.longitude;
    double s = location.speed;
    double l = location.altitude;
    double h = location.horizontalAccuracy;
    double v = location.verticalAccuracy;

    [self locationLog:[NSString stringWithFormat:@"----------------------------------------\n  經度：%.5f\n  緯度：%.5f\n  速度：%.3f Km/H\n  海拔高度：%.1f 公尺\n  水平準度：%.1f 公尺\n  海拔準度：%.1f 公尺\n  目前時間：%@", a, n, s, l, h, v, t]];

    [Path create:@{
                   @"a": [NSString stringWithFormat:@"%f", a],
                   @"n": [NSString stringWithFormat:@"%f", n],
                   @"l": [NSString stringWithFormat:@"%f", l],
                   @"h": [NSString stringWithFormat:@"%f", h],
                   @"v": [NSString stringWithFormat:@"%f", v],
                   @"s": [NSString stringWithFormat:@"%f", s],
                   @"t": t
                   }];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
