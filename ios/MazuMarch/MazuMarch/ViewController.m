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

    self.myDevice = [UIDevice currentDevice];
    [self.myDevice setBatteryMonitoringEnabled:YES];
    
    [self initUI];
    [UIApplication sharedApplication].idleTimerDisabled = YES;
}

- (void)initUI {
    self.marchId = 1;
    self.distance = 1;

    self.locationManager = [CLLocationManager new];
    [self.locationManager setDelegate:self];
    [self.locationManager setDistanceFilter:0];
    [self.locationManager setDesiredAccuracy:kCLLocationAccuracyBest];
    [self.locationManager setAllowsBackgroundLocationUpdates:YES];
    [self.locationManager requestAlwaysAuthorization];
    [self.locationManager setDistanceFilter:self.distance];
    
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
    
    
    self.stepperLabel = [UILabel new];
    [self.stepperLabel setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.stepperLabel setText:[NSString stringWithFormat:@"%d 公尺", self.distance]];

    
    [self.view addSubview:self.stepperLabel];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.stepperLabel attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeRight multiplier:1 constant:-10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.stepperLabel attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.switchButton attribute:NSLayoutAttributeCenterY multiplier:1 constant:0.0]];
    


    self.stepper = [UIStepper new];
    [self.stepper setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.stepper setMaximumValue:10.0];
    [self.stepper setMinimumValue:0];
    [self.stepper setValue:self.distance];
    [self.stepper addTarget:self action:@selector(stepperChanged:) forControlEvents:UIControlEventValueChanged];
    
    
    [self.view addSubview:self.stepper];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.stepper attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.stepperLabel attribute:NSLayoutAttributeLeft multiplier:1 constant:-10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.stepper attribute:NSLayoutAttributeCenterY relatedBy:NSLayoutRelationEqual toItem:self.switchButton attribute:NSLayoutAttributeCenterY multiplier:1 constant:0.0]];
    
    
    
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

- (void)stepperChanged:(UIStepper*)sender {
    self.distance = (int)[sender value];
    [self.stepperLabel setText:[NSString stringWithFormat:@"%d 公尺", self.distance]];
    [self.locationManager setDistanceFilter:self.distance];
    [self locationLog:[NSString stringWithFormat:@"設定 %d 公尺觸發", self.distance]];
    [self locationLog:@"----------------------------------------"];
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
        

        [self.stepper setEnabled:NO];
        [self locationLog:@"鎖定級距器"];
        [self locationLog:@"----------------------------------------"];
        
        self.isUpload = NO;
        self.timer = [NSTimer scheduledTimerWithTimeInterval:PATH_FETCH_TIMER target:self selector:@selector(uploadPaths) userInfo:nil repeats:YES];
        [self uploadLog:@"開啟計時器"];
        [self uploadLog:@"----------------------------------------"];
        

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
        
        [self.stepper setEnabled:YES];
        [self locationLog:@"開啟級距器"];
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
    
    NSDateFormatter *dateFormatter = [NSDateFormatter new];
    [dateFormatter setDateFormat:@"yyyy-MM-dd HH:mm:ss"];
    NSString *t = [dateFormatter stringFromDate:[NSDate date]];
    
    [self uploadLog:@"----------------------------------------"];
    [self uploadLog:[NSString stringWithFormat:@"上傳路徑: %@", t]];
    
    NSMutableDictionary *data = [NSMutableDictionary new];
    NSArray *paths = [Path findAll: @{@"order": @"id DESC", @"limit": @UPLOAD_PATHS_LIMIT}];

    if (((int)[paths count]) < 1) {
        [self uploadLog:@"----------------------------------------"];
        [self uploadLog:@"沒有節點！"];
        [self locationManager: self.locationManager didUpdateLocations: @[self.locationManager.location]];
        [self uploadLog:@"強制取點！"];
        [data setValue:@"1" forKey:@"s"];
    } else {
        [data setValue:@"0" forKey:@"s"];
    }

    NSMutableDictionary *parameters = [NSMutableDictionary new];

    int i = 0;
    for (Path* path in paths) [parameters setValue:[path toDictionary] forKey:[NSString stringWithFormat:@"%d", i++]];
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
                  NSLog(@"=======>Failure!Error:%@", [[NSString alloc] initWithData:(NSData *)error.userInfo[AFNetworkingOperationFailingURLResponseDataErrorKey] encoding:NSUTF8StringEncoding]);
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
    NSString *b = [NSString stringWithFormat:@"%d", (int)ABS ((float)[self.myDevice batteryLevel] * 100)];

    [self locationLog:[NSString stringWithFormat:@"----------------------------------------\n  經度：%.5f\n  緯度：%.5f\n  速度：%.3f Km/H\n  海拔高度：%.1f 公尺\n  水平準度：%.1f 公尺\n  海拔準度：%.1f 公尺\n  目前時間：%@\n 電池電量：%@", a, n, s, l, h, v, t, b]];

    [Path create:@{
                   @"a": [NSString stringWithFormat:@"%f", a],
                   @"n": [NSString stringWithFormat:@"%f", n],
                   @"l": [NSString stringWithFormat:@"%f", l],
                   @"h": [NSString stringWithFormat:@"%f", h],
                   @"v": [NSString stringWithFormat:@"%f", v],
                   @"s": [NSString stringWithFormat:@"%f", s],
                   @"t": t,
                   @"i": @"1",
                   @"b": b
                   }];

    if (((int)[Path count]) >= UPLOAD_PATHS_LIMIT)
        [self uploadPaths];
}

- (void)didReceiveMemoryWarning {
    [super didReceiveMemoryWarning];
    // Dispose of any resources that can be recreated.
}

@end
