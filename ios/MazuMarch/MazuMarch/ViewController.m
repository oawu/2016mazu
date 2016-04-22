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
    self.distance = 3;

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
    

    self.picker = [UIPickerView new];
    [self.picker setDelegate:self];
    [self loadMarches];
    
    self.marchTextField = [UITextField new];
    [self.marchTextField setTranslatesAutoresizingMaskIntoConstraints:NO];
    
    [self.marchTextField.layer setBorderColor:[UIColor colorWithRed:0 green:0 blue:0 alpha:.4].CGColor];
    [self.marchTextField.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
    [self.marchTextField.layer setCornerRadius:2];
    [self.marchTextField setClipsToBounds:YES];

    [self.marchTextField setInputView: self.picker];
    [self.view addSubview:self.marchTextField];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.marchTextField attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.switchButton attribute:NSLayoutAttributeLeft multiplier:1 constant:0.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.marchTextField attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.switchLabel attribute:NSLayoutAttributeBottom multiplier:1 constant:15.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.marchTextField attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeRight multiplier:1 constant:-10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.marchTextField attribute:NSLayoutAttributeHeight relatedBy:NSLayoutRelationEqual toItem:nil attribute:NSLayoutAttributeNotAnAttribute multiplier:1 constant:35.0]];
    
    UIBarButtonItem *doneButton = [[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemDone target:self action:@selector(myResignFirstResponder)];
    UIBarButtonItem *flexibleSpace = [[UIBarButtonItem alloc] initWithBarButtonSystemItem:UIBarButtonSystemItemFlexibleSpace target:nil action:nil];
    UIToolbar *toolbar = [UIToolbar new];
    toolbar.items = @[flexibleSpace, doneButton];
    self.marchTextField.inputAccessoryView = toolbar;
    [toolbar sizeToFit];
    
    
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
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.locationLogTextView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.marchTextField attribute:NSLayoutAttributeBottom multiplier:1 constant:15.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.locationLogTextView attribute:NSLayoutAttributeLeading relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeLeading multiplier:1 constant:10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.locationLogTextView attribute:NSLayoutAttributeTrailing relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTrailing multiplier:1 constant:-10.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.locationLogTextView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.uploadLogTextView attribute:NSLayoutAttributeTop multiplier:1 constant:-10.0]];
    
    self.pswView = [UIView new];
    [self.pswView setTranslatesAutoresizingMaskIntoConstraints:NO];
    [self.pswView setBackgroundColor:[UIColor colorWithRed:0 green:0 blue:0 alpha:.4]];
    
    [self.view addSubview:self.pswView];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.pswView attribute:NSLayoutAttributeTop relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeTop multiplier:1 constant:0.0]];
    self.left1 = [NSLayoutConstraint constraintWithItem:self.pswView attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeRight multiplier:1 constant:0.0];
    self.left2 = [NSLayoutConstraint constraintWithItem:self.pswView attribute:NSLayoutAttributeLeft relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeRight multiplier:0.0001 constant:0.0];
    [self.view addConstraint: self.left1];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.pswView attribute:NSLayoutAttributeWidth relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeWidth multiplier:1 constant:0.0]];
    [self.view addConstraint:[NSLayoutConstraint constraintWithItem:self.pswView attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.view attribute:NSLayoutAttributeBottom multiplier:1 constant:0.0]];
    
    UIButton *btn = [UIButton new];
    [btn setTranslatesAutoresizingMaskIntoConstraints:NO];
//    [btn.layer setBorderColor:[UIColor colorWithRed:0.85 green:0.21 blue:0.20 alpha:1.00].CGColor];
//    [btn.layer setBorderWidth:1.0f / [UIScreen mainScreen].scale];
//    [btn.layer setCornerRadius:2];
//    [btn setClipsToBounds:YES];
    [btn setTitle:@"解鎖" forState:UIControlStateNormal];
    [btn addTarget:self action:@selector(unlock:) forControlEvents:UIControlEventTouchUpInside];
    
    [self.pswView addSubview:btn];
    
    [self.pswView addConstraint:[NSLayoutConstraint constraintWithItem:btn attribute:NSLayoutAttributeRight relatedBy:NSLayoutRelationEqual toItem:self.pswView attribute:NSLayoutAttributeRight multiplier:1 constant:-10.0]];
    [self.pswView addConstraint:[NSLayoutConstraint constraintWithItem:btn attribute:NSLayoutAttributeBottom relatedBy:NSLayoutRelationEqual toItem:self.pswView attribute:NSLayoutAttributeBottom multiplier:1 constant:-10.0]];
    
}
-(void)myResignFirstResponder {
    [self.marchTextField setText:[NSString stringWithFormat:@"  %@", self.marchTitle]];
    [self.marchTextField resignFirstResponder];
}

- (void) loadMarches {
    
    self.marches = [NSMutableArray new];

    [self uploadLog:@"----------------------------------------"];
    [self uploadLog:@"開始取得活動"];

    
    AFHTTPRequestOperationManager *httpManager = [AFHTTPRequestOperationManager manager];
    [httpManager.responseSerializer setAcceptableContentTypes:[NSSet setWithObject:@"application/json"]];
    [httpManager GET:API_POST_MARCHES
           parameters:nil
              success:^(AFHTTPRequestOperation *operation, id responseObject) {
                  [self uploadLog:@"----------------------------------------"];
                  [self uploadLog:@"取得活動成功"];
                  
                  for (NSDictionary *obj in responseObject) {
                      [self.marches addObject: [[March alloc] initWithDictionary: obj]];
                  }
                  
                  if (((int)[self.marches count]) > 0) {
                      self.marchTitle = [self.marches firstObject].title;
                      self.marchId = (int)[[self.marches firstObject].marchId integerValue];
                      [self.marchTextField setText:[NSString stringWithFormat:@"  %@", self.marchTitle]];
                  }
                                    
                  [self.picker reloadAllComponents];
                  
              }
              failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                  self.isUpload = NO;
                  [self locationLog:@"----------------------------------------"];
                  [self locationLog:@"取得活動失敗"];
              }
     ];
}
- (NSInteger)numberOfComponentsInPickerView:(UIPickerView *)pickerView{
    return 1;
}

// returns the # of rows in each component..
- (NSInteger)pickerView:(UIPickerView *)pickerView numberOfRowsInComponent:(NSInteger)component{
    return [self.marches count];
}
- (NSString *)pickerView:(UIPickerView *)pickerView titleForRow:(NSInteger)row forComponent:(NSInteger)component{
    return [NSString stringWithFormat:@"%@ (%@)", [self.marches objectAtIndex:row].title, [self.marches objectAtIndex:row].enable];
}

- (void)unlock:(UIButton *)sender{
    UIAlertController *inputAlert = [UIAlertController
                                     alertControllerWithTitle:@"輸入密碼"
                                     message:nil
                                     preferredStyle:UIAlertControllerStyleAlert];

    [inputAlert addTextFieldWithConfigurationHandler:^(UITextField *text){
        [text setPlaceholder:@"請輸入密碼解鎖.."];
        [text setSecureTextEntry:YES];
    }];
    [inputAlert addAction:[UIAlertAction
                           actionWithTitle:@"確定"
                           style:UIAlertActionStyleDefault
                           handler:^(UIAlertAction * action) {
                               if ([((UITextField *)[inputAlert.textFields objectAtIndex:0]).text isEqualToString:@"oa0319"]) {
                                   [UIView animateWithDuration:0.3f animations:^{
                                       [self.view addConstraint: self.left1];
                                       [self.view removeConstraint:self.left2];
                                       [self.view layoutIfNeeded];
                                   } completion:nil];
                               } else {
//                                   [self presentViewController:inputAlert animated:YES completion:nil];
                               }
                           }]];
    [self presentViewController:inputAlert animated:YES completion:nil];
}

- (void)stepperChanged:(UIStepper*)sender {
    self.distance = (int)[sender value];
//      [self.stepper setValue:self.distance];
    [self.stepperLabel setText:[NSString stringWithFormat:@"%d 公尺", self.distance]];
    [self.locationManager setDistanceFilter:self.distance];
    [self locationLog:[NSString stringWithFormat:@"設定 %d 公尺觸發", self.distance]];
    [self locationLog:@"----------------------------------------"];
}


- (void)pickerView:(UIPickerView *)pickerView didSelectRow:(NSInteger)row inComponent:(NSInteger)component {
    self.marchTitle = [self.marches objectAtIndex:row].title;
    self.marchId = (int)[[self.marches objectAtIndex:row].marchId integerValue];
    [self.marchTextField setText:[NSString stringWithFormat:@"  %@", self.marchTitle]];
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
        
        
        [self.marchTextField setEnabled:NO];
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

        [UIView animateWithDuration:0.3f animations:^{
            [self.view addConstraint: self.left2];
            [self.view removeConstraint:self.left1];
            [self.view layoutIfNeeded];
        } completion:nil];
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
        
        [self.marchTextField setEnabled:YES];
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

                  int d = (int)[[responseObject objectForKey:@"d"] integerValue];
                  if (self.distance != d) {
                      self.distance = d;
                      [self.stepperLabel setText:[NSString stringWithFormat:@"%d 公尺", self.distance]];
                      [self.stepper setValue:self.distance];
                      [self.locationManager setDistanceFilter:self.distance];
                      [self locationLog:[NSString stringWithFormat:@"設定 %d 公尺觸發", self.distance]];
                      [self locationLog:@"----------------------------------------"];
                  }

              }
              failure:^(AFHTTPRequestOperation *operation, NSError *error) {
                  self.isUpload = NO;
                  [self uploadLog:@"----------------------------------------"];
                  [self uploadLog:@"上傳失敗"];
//                  NSLog(@"=======>Failure!Error:%@", [[NSString alloc] initWithData:(NSData *)error.userInfo[AFNetworkingOperationFailingURLResponseDataErrorKey] encoding:NSUTF8StringEncoding]);
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
